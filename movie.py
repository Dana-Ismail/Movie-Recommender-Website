import pandas as pd
import os
import json
from sqlalchemy import create_engine
from sqlalchemy.engine import Engine
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def prepare_data():
    connection_parameters = {
        'host': os.getenv('DB_HOST', '127.0.0.1'),
        'user': os.getenv('DB_USERNAME', 'forge'),
        'password': os.getenv('DB_PASSWORD', ''),
        'database': os.getenv('DB_DATABASE', 'forge'),
    }

    # Create the SQLAlchemy engine
    engine: Engine = create_engine(f"mysql+mysqlconnector://{connection_parameters['user']}:{connection_parameters['password']}@{connection_parameters['host']}/{connection_parameters['database']}")

    # Get movie data
    movies_query = "SELECT id, Title, Genre1, Genre2, Genre3, Tags_1, Tags_2, Tags_3, Tags_4, Tags_5, Tags_6, " \
                   "Director_1, Director_2, Actors_1, Actors_2, Actors_3, Actors_4 FROM movies"
    with engine.connect() as con:
        movies_df = pd.read_sql(movies_query, con=con)

    return movies_df

def build_content_based_model(movies_df):
    # Create separate feature columns for each category
    genre_features = movies_df[['Genre1', 'Genre2', 'Genre3']].apply(lambda x: ' '.join(x.dropna().astype(str)), axis=1)
    tag_features = movies_df[['Tags_1', 'Tags_2', 'Tags_3', 'Tags_4', 'Tags_5', 'Tags_6']].apply(lambda x: ' '.join(x.dropna().astype(str)), axis=1)
    director_features = movies_df[['Director_1', 'Director_2']].apply(lambda x: ' '.join(x.dropna().astype(str)), axis=1)
    actor_features = movies_df[['Actors_1', 'Actors_2', 'Actors_3', 'Actors_4']].apply(lambda x: ' '.join(x.dropna().astype(str)), axis=1)

    # Create TfidfVectorizer for each feature column
    tfidf_vectorizer_genre = TfidfVectorizer()
    tfidf_vectorizer_tag = TfidfVectorizer()
    tfidf_vectorizer_director = TfidfVectorizer()
    tfidf_vectorizer_actor = TfidfVectorizer()

    # Vectorize each feature separately
    genre_vectors = tfidf_vectorizer_genre.fit_transform(genre_features)
    tag_vectors = tfidf_vectorizer_tag.fit_transform(tag_features)
    director_vectors = tfidf_vectorizer_director.fit_transform(director_features)
    actor_vectors = tfidf_vectorizer_actor.fit_transform(actor_features)

    # Calculate the cosine similarity matrices for each feature
    similarity_matrix_genre = cosine_similarity(genre_vectors)
    similarity_matrix_tag = cosine_similarity(tag_vectors)
    similarity_matrix_director = cosine_similarity(director_vectors)
    similarity_matrix_actor = cosine_similarity(actor_vectors)

    return similarity_matrix_genre, similarity_matrix_tag, similarity_matrix_director, similarity_matrix_actor

def get_movie_index_from_id(movie_id, movies_df):
    return movies_df[movies_df['id'] == movie_id].index[0]

def get_similar_movies(similarity_matrix, movie_index):
    similarity_scores = similarity_matrix[movie_index]
    similar_movies_indices = [index for index, score in enumerate(similarity_scores)]
    similar_movies_indices.remove(movie_index)
    return similar_movies_indices[:3]

def combine_similarity_scores(scores_list, weights):
    combined_scores = sum([score * weight for score, weight in zip(scores_list, weights)])
    return combined_scores

def get_recommendations(movie_id):
    movies_df = prepare_data()

    movie_index = get_movie_index_from_id(movie_id, movies_df)
    similarity_matrix_genre, similarity_matrix_tag, similarity_matrix_director, similarity_matrix_actor = build_content_based_model(movies_df)

    # Get similar movies for each feature separately
    similar_movies_indices_genre = get_similar_movies(similarity_matrix_genre, movie_index)
    similar_movies_indices_tag = get_similar_movies(similarity_matrix_tag, movie_index)
    similar_movies_indices_director = get_similar_movies(similarity_matrix_director, movie_index)
    similar_movies_indices_actor = get_similar_movies(similarity_matrix_actor, movie_index)

    # Combine similarity scores from all features with equal weights (you can adjust weights as needed)
    weights = [1, 1, 1, 1]  # Equal weights for each feature
    all_similar_movies_indices = list(set(similar_movies_indices_genre + similar_movies_indices_tag +
                                          similar_movies_indices_director + similar_movies_indices_actor))

    # Calculate the combined similarity scores
    combined_similarity_scores = combine_similarity_scores(
        [similarity_matrix_genre[movie_index, all_similar_movies_indices],
         similarity_matrix_tag[movie_index, all_similar_movies_indices],
         similarity_matrix_director[movie_index, all_similar_movies_indices],
         similarity_matrix_actor[movie_index, all_similar_movies_indices]],
        weights
    )

    # Sort the movies based on combined similarity scores and get top 3 recommended movies
    top_indices = sorted(enumerate(all_similar_movies_indices), key=lambda x: combined_similarity_scores[x[0]], reverse=True)[:3]
    recommended_movie_ids = [movies_df.iloc[idx]['id'] for _, idx in top_indices]

    recommended_movies = movies_df[movies_df['id'].isin(recommended_movie_ids)][['id', 'Title']].to_dict(orient='records')
    recommended_movies_json = json.dumps(recommended_movies)
    print(recommended_movies_json)
    print("Recommended Movies:")
    for movie in recommended_movies:
        print(f"Movie ID: {movie['id']}, Title: {movie['Title']}")

    return recommended_movies
if __name__ == "__main__":
    # Replace 1 with the movie ID you want to get recommendations for
    movie_id = 1
    get_recommendations(movie_id)
