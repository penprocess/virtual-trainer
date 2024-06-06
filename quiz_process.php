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
            background-color: #ffffff;
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
            margin-bottom: 20px;
            font-size: 18px;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
      
       
.buttons-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            margin-bottom: 10px;
           
    
        }
        .button-wrapper {
    display: flex;
    margin-right:10px;
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



.back-arrow::before {
    content: "\00ab"; 
    margin-right: 5px;
    font-family: "Font Awesome 5 Free";
}

        .back-button:hover {
            background-color: #808085;
            transform: scale(1.05);
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .back-button:active,
        button:active {
            transform: scale(0.95);
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
      
        $question = $_POST['question'];
        $user_answer = $_POST['user_answer'];
       
        $pythonExecutable = '/home/penandprocess/virtualenvs/myenv/bin/python';
$val_script = "/home/penandprocess/public_html/vt.penandprocess.com/quiz/fine.py";
        $command = "$pythonExecutable $val_script \"$question\" \"$user_answer\" 2>&1";
        $validation_output = shell_exec($command);

        echo "<p>" . nl2br($validation_output) . "</p>";
        ?>
        <form action="quiz_continue.php" method="post" onsubmit="showLoader()">
        <div class="buttons-container">
            <div class="button-wrapper">
        <a href="home.php" class="back-button"><span class="back-arrow"></span>Back</a>
            <button type="submit" name="continue" onclick="showLoader()">Continue</button>
    </div>
    </div>
    <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>
   
        </form>
        
        
        
    </div>
    
    <script>
    function showLoader() {
        // Show the loader container
        document.getElementById("loaderContainer").style.display = "block";
        
        // Add the 'loader' class to start the animation
        document.querySelector('.loader').classList.add('loader');
        
        // Disable the buttons
        const buttons = document.querySelectorAll('button[type="submit"]');
        buttons.forEach(button => {
            button.classList.add('loading-button');
            button.disabled = true;
        });
        
        // Submit the form after showing the loader
        document.querySelector('form').submit();
    }
</script>


 


   
</body>
</html>