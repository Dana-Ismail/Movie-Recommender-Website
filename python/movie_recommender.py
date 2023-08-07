import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np
import os,json
from bottle import Bottle, response, run
from sqlalchemy import create_engine

def prepare_data():
    connection_parameters = {
        'host': os.getenv('DB_HOST', '127.0.0.1'),
        'username': os.getenv('DB_USERNAME', 'root'),
        'password': os.getenv('DB_PASSWORD', ''),
        'database': os.getenv('DB_DATABASE', 'laravel'),
    }

    # create a database connection using sqlalchemy
    db_url = f"mysql+pymysql://{connection_parameters['username']}:{connection_parameters['password']}@{connection_parameters['host']}/{connection_parameters['database']}"
    engine = create_engine(db_url)
    # get favorite movies from the "favorite_movies" table
    favorite_movies_query = "SELECT user_id, movie_id FROM favorite_movies"
    favorite_movies_df = pd.read_sql_query(favorite_movies_query, con=engine)
    # get all movie details from the "movies" table
    movies_query = "SELECT id, Title, Genre1, Genre2, Genre3, Tags_1, Tags_2, Tags_3, Tags_4, Tags_5, Tags_6, Type, IMDb_Score, Actors_1, Actors_2, Actors_3, Actors_4, Director_1, Director_2 FROM movies"
    movies_df = pd.read_sql_query(movies_query, con=engine)
    # close the database connection
    engine.dispose()

    return favorite_movies_df, movies_df


# Collaborative Filtering 
dataset = pd.read_csv('ratings_small.csv')
user_item_matrix = dataset.pivot(index='userId', columns='movieId', values='rating').fillna(0)
user_similarity = cosine_similarity(user_item_matrix)
np.fill_diagonal(user_similarity, 0)  # to avoid recommending user's movies
user_similarity_df = pd.DataFrame(user_similarity, index=user_item_matrix.index, columns=user_item_matrix.index)

def get_user_recommendations(user_id, num_recommendations=5):
    similar_users = user_similarity_df[user_id].nlargest(num_recommendations + 1).index  # +1 to exclude the user itself
    user_movies = user_item_matrix.loc[user_id]
    recommendations = []

    for user in similar_users:
        similar_user_movies = user_item_matrix.loc[user]
        unseen_movies = similar_user_movies[user_movies == 0]  # filter movies the target user has not seen
        recommendations.extend(unseen_movies.nlargest(num_recommendations).index)

    return recommendations


# Content Based 
def build_content_based_model(movies_df, genre_weight=1.0, tags_weight=0.8, type_weight=0.75, actors_weight=0.55, directors_weight=0.4):
    # separate the feature columns based on their types
    genres = movies_df[['Genre1', 'Genre2', 'Genre3']].astype(str)
    tags = movies_df[['Tags_1', 'Tags_2', 'Tags_3', 'Tags_4', 'Tags_5', 'Tags_6']].astype(str)
    types = movies_df['Type'].astype(str)
    actors = movies_df[['Actors_1', 'Actors_2', 'Actors_3', 'Actors_4']].astype(str)
    directors = movies_df[['Director_1', 'Director_2']].astype(str)
    # convert each feature into text representations
    genres_text = genres.apply(lambda x: ' '.join(x), axis=1)
    tags_text = tags.apply(lambda x: ' '.join([tag for tag in x if tag != 'None']), axis=1)
    types_text = types
    actors_text = actors.apply(lambda x: ' '.join([actor for actor in x if actor != 'None']), axis=1)
    directors_text = directors.apply(lambda x: ' '.join([director for director in x if director != 'None']), axis=1)
    # create TfidfVectorizer for each feature
    max_features = 50
    tfidf_vectorizer_genres = TfidfVectorizer(stop_words='english', max_features=max_features, ngram_range=(1, 1))
    tfidf_vectorizer_tags = TfidfVectorizer(stop_words='english', max_features=max_features, ngram_range=(1, 1))
    tfidf_vectorizer_types = TfidfVectorizer(stop_words='english', max_features=max_features, ngram_range=(1, 1))
    tfidf_vectorizer_actors = TfidfVectorizer(stop_words='english', max_features=max_features, ngram_range=(1, 1))
    tfidf_vectorizer_directors = TfidfVectorizer(stop_words='english', max_features=max_features, ngram_range=(1, 1))

    # calculate the TF-IDF vectors for each feature
    genres_vectors = tfidf_vectorizer_genres.fit_transform(genres_text)
    tags_vectors = tfidf_vectorizer_tags.fit_transform(tags_text)
    types_vectors = tfidf_vectorizer_types.fit_transform(types_text)
    actors_vectors = tfidf_vectorizer_actors.fit_transform(actors_text)
    directors_vectors = tfidf_vectorizer_directors.fit_transform(directors_text)
    # apply feature weights to each feature vector and concatenate them
    weighted_feature_vectors = np.hstack((
        genres_vectors.multiply(genre_weight).toarray(),
        tags_vectors.multiply(tags_weight).toarray(),
        types_vectors.multiply(type_weight).toarray(),
        actors_vectors.multiply(actors_weight).toarray(),
        directors_vectors.multiply(directors_weight).toarray()
    ))
    # calculate the cosine similarity 
    similarity_matrix_content = cosine_similarity(weighted_feature_vectors)

    return similarity_matrix_content

def get_similar_movies(similarity_matrix, movie_index, num_recommendations=5, similarity_threshold=0.5):
    similarity_scores = similarity_matrix[movie_index]
    # sort the similarity scores in descending order and get similar movies with a similarity threshold
    similar_movies_indices = [idx for idx, score in enumerate(similarity_scores) if score > similarity_threshold]
    # exclude the input movie itself
    similar_movies_indices = [idx for idx in similar_movies_indices if idx != movie_index]
    # sort the movies by similarity score and return top similar movies
    similar_movies_indices = sorted(similar_movies_indices, key=lambda x: similarity_scores[x], reverse=True)
    return similar_movies_indices[:num_recommendations]

def content_based_recommendations(user_id):
    favorite_movies_df, all_movies_df = prepare_data()
    # filter favorite movies for the specified user
    user_favorite_movies = favorite_movies_df[favorite_movies_df['user_id'] == user_id]['movie_id'].tolist()
    # Content-Based filtering with custom feature weights using the entire movies_df
    similarity_matrix_content = build_content_based_model(all_movies_df, genre_weight=1.0, tags_weight=0.8, type_weight=0.75, actors_weight=0.55, directors_weight=0.4)
    
    recommended_movies = []
    for movie_id in user_favorite_movies:
        # get the index of the movie in all_movies_df
        movie_index = all_movies_df.index[all_movies_df['id'] == movie_id].tolist()[0]
        # get similar movie indices using content-based model for the current movie
        similar_movies_indices = get_similar_movies(similarity_matrix_content, movie_index)
        # get recommended movies details from all_movies_df using the similar movie indices
        recommended_movies_for_movie = all_movies_df.iloc[similar_movies_indices][['id', 'Title']].to_dict(orient='records')
        # remove the current movie from the recommendations if exists
        recommended_movies_for_movie = [movie for movie in recommended_movies_for_movie if movie['id'] != movie_id]
        # 3 unique recommendations for the movie
        unique_recommendations = []
        for rec_movie in recommended_movies_for_movie:
            if len(unique_recommendations) >= 3:
                break
            if rec_movie['id'] not in user_favorite_movies:
                unique_recommendations.append(rec_movie['id']) 

        recommended_movies.extend(unique_recommendations)

    return recommended_movies


# Hybrid Recommender System
def hybrid_recommendations(user_id):
    collaborative_recommendations = get_user_recommendations(user_id)
    content_based_recommendations = content_based_recommendations(user_id)
    # combine and remove duplicates
    all_recommendations = list(set(collaborative_recommendations + content_based_recommendations))

    return all_recommendations

app = Bottle()

@app.route('/recommendations/<user_id>')
def get_movie_recommendations(user_id):
    recommended_movies = content_based_recommendations(int(user_id))
    recommended_movies_json = json.dumps(recommended_movies) 
    response.content_type = 'application/json'
    return recommended_movies_json

if __name__ == '__main__':
    run(app, host='localhost', port=8080)
