<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI Quiz</title>
    <style>
      body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-color: #fff;
}

.container {
    width: 600px; /* Set a fixed width */
    height: 400px; /* Set a fixed height */
    padding: 40px;
    border-radius: 10px;
    background-color: #fff;
    overflow-y: auto; /* Add vertical scrollbar if content exceeds container height */
}


p {
    margin-bottom: 20px; /* Reduce margin between question and form */
    font-size: 20px;
}

form {
    margin-bottom: 20px;
}

input[type="text"],
input[type="submit"] {
    width: calc(100% - 22px); /* Take into account padding and border */
    padding: 15px;
    margin-bottom: 15px;
    border: 2px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 15px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

input[type="submit"][disabled] {
    cursor: not-allowed;
    opacity: 0.7;
    background-color: grey;
}



#evaluationMessage {
    display: none;
    color: #777;
    font-size: 18px;
}


button {
            padding: 3px 10px;
            background-color: #007fff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 15px;
            border: none;
            cursor: pointer;
            outline: none;
            position: relative;
            margin-right: 10px;
        }
        
        
        .buttons-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            margin-bottom: 10px;
           
           
    
        }
       
        .button-wrapper {
    display: flex;
    margin-right: 15px;
}

.back-arrow::before {
    content: "\00ab"; 
    margin-right: 5px;
    font-family: "Font Awesome 5 Free";
}
        .back-button {
    display: flex;
    padding: 5px 10px;
    background-color: grey;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.2s;
    font-size: 13px;
    border: none;
    cursor: pointer;
    outline: none;
    position: relative;
    margin-right: 10px;
  
   
    
}

button:disabled{
    background-color: grey;
    pointer-events:none;
}

button:enabled:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }


        .back-button:hover{
            background-color: #808085;
            transform: scale(1.05);
        }

        .back-button:active,
        button:enabled:active {
            transform: scale(0.95);
        }
        
        .loading-button {
            background-color: #cccccc;
            color: #666666;
            pointer-events: none; /* Disable further clicks when loading */
        }

        .loader-container {
            text-align: center;
            margin-top: 20px;
            display: none;
        }

        



        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-left: auto;
            margin-right: auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }


    </style>
</head>
<body>
<div class="container">

<?php

// Call Python script to get the question
$pythonExecutable = '/home/penandprocess/virtualenvs/myenv/bin/python';
$pythonScript = "/home/penandprocess/public_html/vt.penandprocess.com/quiz/main.py";
$command = "$pythonExecutable $pythonScript 2>&1";
$output = shell_exec($command);
$output_array = explode(",", $output);
$question = trim($output_array[0]);
$question = str_replace("'", "", $question);
$question = str_replace("(", "", $question);


// Store the question and expected answer in session variables

$_SESSION['question'] = $question;

$question = $_SESSION['question'];
echo "<p>$question</p>";

// Display form to enter answer
?>
 <form id="quizForm" action="quiz_process.php" method="post" onsubmit="return validateForm()">
    <!-- Pass the question along with the form -->
    <input type="hidden" name="question" value="<?= htmlspecialchars($question) ?>">
    <input type="text" id="user_answer" placeholder="Enter Your Answer:" name="user_answer">
    <br>
    <span id="answerError" style="color: red; display: none;">Please fill in the field.</span>
    <br>
    <div class="buttons-container">
        <div class="button-wrapper">
            <a href="home.php" class="back-button"><span class="back-arrow"></span>Back</a>
            <button type="submit" id="submitButton" name="submit_answer" onclick="showLoader()" disabled>Submit Answer</button> <!-- Button initially disabled -->
        </div>
    </div>
    <!-- Loader and message -->
    <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>
</form>

<script>
    // Get the input field and submit button
    const userAnswerInput = document.getElementById("user_answer");
    const submitButton = document.getElementById("submitButton");

    // Add an event listener to the input field
    userAnswerInput.addEventListener("input", function() {
        // If the input field is not empty, enable the submit button
        if (userAnswerInput.value.trim() !== "") {
            submitButton.removeAttribute("disabled");
        } else {
            // Otherwise, disable the submit button
            submitButton.setAttribute("disabled", "true");
        }
    });
</script>



</div>

<script>
    // JavaScript function to validate form before submission
    function validateForm() {
        var answerInput = document.getElementById("user_answer").value.trim();
        var submitButton = document.querySelector('input[type="submit"]');
        var answerError = document.getElementById("answerError");
        if (answerInput === "") {
            answerError.style.display = "block";
            return false; // Prevent form submission
        } else {
            answerError.style.display = "none";
            return true; // Allow form submission
        }
    }

    // Enable/disable submit button based on input field value
    document.getElementById("user_answer").addEventListener("input", function() {
        var answerInput = document.getElementById("user_answer").value.trim();
        var submitButton = document.querySelector('input[type="submit"]');
        var answerError = document.getElementById("answerError");
        if (answerInput !== "") {
            submitButton.removeAttribute("disabled");
            answerError.style.display = "none";
        } else {
            submitButton.setAttribute("disabled", "true");
            answerError.style.display = "none"; // Hide error message while typing
        }
    });


    function showLoader() {
        document.getElementById("loaderContainer").style.display = "block";
        const buttons = document.querySelectorAll('button[type="submit"]');
        buttons.forEach(button => {
            button.classList.add('loading-button');
            button.disabled = true; // Disable the button while loading
            button.innerHTML = '<span style="color: #ffffff;">Evaluating...</span>'; // Change the button text with color
        });
        // Trigger form submission after showing loader
        document.getElementById("quizForm").submit();
    }



</script>

</body>
</html>