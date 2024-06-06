import openai
import os
from dotenv import load_dotenv 
import sys

def get_answer(query, model="gpt-3.5-turbo-instruct", max_tokens=200):
    load_dotenv()
    openai.api_key = os.getenv('OPENAI_API_KEY')

    with open("text.txt", 'r', encoding='utf-8') as file:
        content = file.read()

    response = openai.Completion.create(
        engine=model,
        prompt=f"Provide answers to the user's question only with the information available in the document, otherwise say 'Information Unavailable'.\nDocument Content: {content}\nQuestion: {query}\nAnswer:",
        temperature=0.1,
        max_tokens=max_tokens,
        top_p=1.0,
        frequency_penalty=0.0,
        presence_penalty=0.0,
        stop=["\n"]
    )
    return response.choices[0].text.strip()


if __name__ == "__main__":
    if len(sys.argv) == 2:
        question = sys.argv[1]
        result = get_answer(question)
        print(result)
