import os
import pandas as pd
import re
from bs4 import BeautifulSoup
import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.sentiment.vader import SentimentIntensityAnalyzer
from sqlalchemy import create_engine
from bottle import Bottle, request, response, run
import json
nltk.download('punkt')
nltk.download('vader_lexicon')
nltk.download('stopwords')

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
    user_reviews_query = "SELECT user_id, movie_id, comment FROM reviews"
    reviews_df = pd.read_sql_query(user_reviews_query, con=engine)
    # close the database connection
    engine.dispose()

    return reviews_df

def clean_text(text):
    # remove HTML tags
    text = BeautifulSoup(text, 'html.parser').get_text()
    # remove non-alphabetic characters
    text = re.sub("[^a-zA-Z]", " ", text)
    # lowercasing 
    text = text.lower()
    # tokenization
    words = word_tokenize(text)
    # remove stop words
    stop_words = set(stopwords.words("english"))
    words = [word for word in words if word not in stop_words]
    cleaned_text = " ".join(words)
    
    return cleaned_text

def get_sentiment_score(text):
    analyzer = SentimentIntensityAnalyzer()
    # get sentiment scores using VADER
    return analyzer.polarity_scores(text)['compound']

def analyze_sentiment(reviews_df):
    # apply text cleaning to the 'comment' column
    reviews_df['cleaned_comment'] = reviews_df['comment'].apply(clean_text)
    # get sentiment scores for each comment
    reviews_df['sentiment_score'] = reviews_df['cleaned_comment'].apply(get_sentiment_score)
    return reviews_df[['user_id', 'movie_id', 'comment', 'sentiment_score']]

def analyze_sentiment_api():
    if request.method == 'GET':
        reviews_df = prepare_data()
        analyzed_reviews_df = analyze_sentiment(reviews_df)
        response_data = analyzed_reviews_df.to_dict(orient='records')
        return json.dumps(response_data)
    elif request.method == 'POST':
        data = request.json  
        # Assuming the POST data contains 'comment' field
        comment = data['comment']
        
        # Perform sentiment analysis on the comment
        sentiment_score = get_sentiment_score(comment)
        
        # Prepare the response data
        response_data = {
            'comment': comment,
            'sentiment_score': sentiment_score,
        }

        # Set the content type and return the response
        response.content_type = 'application/json'
        return json.dumps(response_data)

app = Bottle()

@app.route('/sentiment')
def get_sentiment_scores():
    return analyze_sentiment_api()

if __name__ == '__main__':
    run(app, host='localhost', port=8090)