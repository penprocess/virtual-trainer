# Import necessary libraries and functions
from main import openai_chat
import openai
import sys
import os
from dotenv import load_dotenv

# Function to validate an answer to a given question using OpenAI's API
def validation(que, ans):
    # Load environment variables from a .env file
    load_dotenv()
    # Get the OpenAI API key from the environment variables
    openai.api_key = os.getenv('OPENAI_API_KEY')
    
    # Define the job ID for the fine-tuning job 
    job_id = "ftjob-J5QxRCYUSKvvflR5BAx01Jtr"
    
    # List the latest 10 events from the fine-tuning job
    openai.FineTuningJob.list_events(id=job_id, limit=10)
    # Retrieve the fine-tuning job details 
    model_name_pre_object = openai.FineTuningJob.retrieve(job_id)
    
    # Create a chat completion request to the OpenAI API
    response = openai.ChatCompletion.create(
        model=model_name_pre_object,  # Specify the model to use
        messages=[
            {
                "role": "system",
                "content": "You are a teacher. Given an answer about the process of hydrocarbon processing and the use of fired heaters, evaluate the accuracy of the information provided, pointing out any major contextual or language errors and respond with the correct answer. If the answer is correct, say it's correct. Otherwise say why it is wrong. Do not respond with a question",
            },
            {
                "role": "system",
                "content": que,  # Provide the question to the model
            },
            {
                "role": "user",
                "content": ans,  # Provide the user's answer to the model
            }
        ],
    )
    
    # Return the content of the model's response
    return response.choices[0].message['content']

# Main execution block to run the validation function if the script is executed directly
if __name__ == "__main__":
    # Check if two command line arguments (question and user's answer) are provided
    if len(sys.argv) == 3:
        question = sys.argv[1]  # First command line argument is the question
        user = sys.argv[2]  # Second command line argument is the user's answer
        # Validate the user's answer to the question and print the result
        result = validation(question, user)
        print(result)