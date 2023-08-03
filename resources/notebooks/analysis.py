import pandas as pd
import numpy as np

def load_movie_data(file_path):
    df = pd.read_excel(file_path)
    df = df.rename(columns={'Series or Movie': 'Type'})
    return df

def remove_columns_with_high_null_percentage(df, threshold=0.5):
    null_column_threshold = len(df) * threshold
    null_counts = df.isnull().sum()
    columns_to_delete = null_counts[null_counts >= null_column_threshold].index
    return df.drop(columns=columns_to_delete)

def extract_and_select_genres(df):
    max_genres = df['Genre'].str.split(',', expand=True).apply(lambda x: x.notna().sum(), axis=1).max()
    genre_columns = [f'Genre{i+1}' for i in range(max_genres)]
    split_genres = df['Genre'].str.split(',', expand=True)
    split_genres.columns = genre_columns

    selected_genre_columns = []
    for _, row in split_genres.iterrows():
        non_null_genres = row.dropna().values
        genre_count = len(non_null_genres)
        if genre_count > 0:
            if genre_count >= 3:
                selected_genres = np.random.choice(non_null_genres, size=3, replace=False)
            else:
                selected_genres = np.random.choice(non_null_genres, size=3, replace=True)
            selected_genre_columns.append(selected_genres)
        else:
            selected_genre_columns.append([None] * 3)

    selected_genre_columns = np.array(selected_genre_columns)
    df = pd.concat([df, pd.DataFrame(selected_genre_columns, columns=['Genre1', 'Genre2', 'Genre3'])], axis=1)
    df = df.drop(columns=['Genre'])
    return df

def extract_languages(df):
    max_languages = df['Languages'].str.split(',', expand=True).apply(lambda x: x.notna().sum(), axis=1).max()
    language_columns = [f'Language{i+1}' for i in range(max_languages)]
    split_languages = df['Languages'].str.split(',', expand=True)
    split_languages.columns = language_columns

    languages_to_keep = ['Language_1', 'Language_2']
    split_languages = split_languages[languages_to_keep]

    # remove spaces from language columns
    for column in split_languages.columns:
        split_languages[column] = split_languages[column].str.replace(' ', '')

    return split_languages
def extract_directors(df):
    max_directors = 2
    director_columns = [f'Director{i+1}' for i in range(max_directors)]
    split_directors = df['Director'].str.split(',', expand=True)

    # limit each row to a maximum of 2 directors
    split_directors = split_directors.apply(lambda row: row.dropna().tolist()[:2], axis=1)
    split_directors = split_directors.apply(pd.Series)
    split_directors.columns = director_columns

    # remove spaces from director values
    split_directors = split_directors.applymap(lambda x: x.strip() if isinstance(x, str) else x)

    return split_directors

def extract_actors(df):
    max_actors = 5
    actor_columns = [f'Actor{i+1}' for i in range(max_actors)]
    split_actors = df['Actors'].str.split(',', expand=True)

    # Limit each row to a maximum of 5 actors
    split_actors = split_actors.apply(lambda row: row.dropna().tolist()[:5], axis=1)
    split_actors = split_actors.apply(pd.Series)
    split_actors.columns = actor_columns

    # remove spaces from actor values
    split_actors = split_actors.applymap(lambda x: x.strip() if isinstance(x, str) else x)

    return split_actors

def filter_popular_movies(df, imdb_threshold=8, rotten_threshold=80, awards_threshold=None):
    if awards_threshold is None:
        awards_threshold = df['Awards Received'].mean()
    popular_movies = df[(df['IMDb Score'] >= imdb_threshold) &
                        (df['Rotten Tomatoes Score'] >= rotten_threshold) &
                        (df['Awards Received'] + df['Awards Nominated For'] >= awards_threshold)]
    popular_movies_df = popular_movies['MovieID']
    return popular_movies_df

def filter_trending_movies(df, current_year):
    trending_years = [current_year - 1, current_year - 2, current_year - 3]
    trending_movies = df[df['Year'].isin(trending_years)]
    trending_movies_dataset = trending_movies['MovieID']
    return trending_movies_dataset

def clean_movie_data(df, genre_dict):
    # remove spaces from Genre1, Genre2, and Genre3 columns
    df['Genre1'] = df['Genre1'].str.replace(' ', '')
    df['Genre2'] = df['Genre2'].str.replace(' ', '')
    df['Genre3'] = df['Genre3'].str.replace(' ', '')

    # replace Genre1, Genre2, and Genre3 values with dictionary values
    df['Genre1'] = df['Genre1'].replace(genre_dict)
    df['Genre2'] = df['Genre2'].replace(genre_dict)
    df['Genre3'] = df['Genre3'].replace(genre_dict)

    # replace 'None' with NaN in Genre1, Genre2, and Genre3 columns
    df['Genre1'] = df['Genre1'].replace('None', np.nan)
    df['Genre2'] = df['Genre2'].replace('None', np.nan)
    df['Genre3'] = df['Genre3'].replace('None', np.nan)

    # convert Genre IDs to integers and handle missing genre entries
    df['Genre1'] = df['Genre1'].fillna(0).astype(int).replace(0, None)
    df['Genre2'] = df['Genre2'].fillna(0).astype(int).replace(0, None)
    df['Genre3'] = df['Genre3'].fillna(0).astype(int).replace(0, None)
    df.drop('MovieID', axis=1, inplace=True)
    df = df.drop('IMDb_Votes', axis=1)
    df.reset_index(drop=True, inplace=True)

    return df

def create_genre_df(df):
    genre_columns = ['Genre1', 'Genre2', 'Genre3']
    unique_genres = pd.unique(df[genre_columns].apply(lambda x: x.str.strip()).values.ravel())
    unique_genres = [genre for genre in unique_genres if genre is not None]
    genre_df = pd.DataFrame({'Genre': unique_genres})
    genre_df['GenreID'] = range(1, len(genre_df) + 1)
    genre_df['GenreID'] = genre_df['GenreID'].astype(int)
    return genre_df

def create_genre_dict(genre_df):
    genre_dict = {genre: genre_id for genre, genre_id in zip(genre_df['Genre'], genre_df['GenreID'])}
    genre_dict[None] = None 
    return genre_dict

def remove_movie_by_title(df, movie_title):
    matching_movies = df[df['Title'] == movie_title]
    if not matching_movies.empty:
        index = matching_movies.index[0]
        df.drop(index, inplace=True)
        df.reset_index(drop=True, inplace=True)
    return df

data_file = "/Movie/MovieRecommender/resources/MovieDataset.xlsx"
movies_data = load_movie_data(data_file)
movies_data = remove_columns_with_high_null_percentage(movies_data)
movies_data = extract_and_select_genres(movies_data)
directors_data = extract_directors(movies_data)
movies_data = pd.concat([movies_data, directors_data], axis=1)
languages_data = extract_languages(movies_data)
movies_data = pd.concat([movies_data, languages_data], axis=1)
actors_data = extract_actors(movies_data)
movies_data = pd.concat([movies_data, actors_data], axis=1)
genre_df = create_genre_df(movies_data)
genre_dict = create_genre_dict(genre_df)
popular_movies_data = filter_popular_movies(movies_data)
trending_movies_data = filter_trending_movies(movies_data, current_year=2023)
movies_data = clean_movie_data(movies_data, genre_dict)
movie_title_to_remove = "Partners: The Movie IV"
movies_data = remove_movie_by_title(movies_data, movie_title_to_remove)
movies_data.to_csv('movies.csv', index=False)
popular_movies_data.to_csv('popular_movies.csv', index=False)
trending_movies_data.to_csv('trending_movies.csv', index=False)