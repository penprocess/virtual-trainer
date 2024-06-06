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
            width: 500px;
            min-height: 300px;
            padding: 20px;
            border-radius: 5px;
            background-color: #fff;
            overflow: auto;
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
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
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
    $count_file = 'count.txt';

    if (isset($_POST['reset_quiz'])) {
        file_put_contents($count_file, '1');
        header("Location: quiz_start.php");
        exit();
    }

    if (file_exists($count_file)) {
        $count = (int)file_get_contents($count_file);
    } else {
        $count = 1;
        file_put_contents($count_file, $count);
    }

    $count++;
    file_put_contents($count_file, $count);

    if ($count<= 3) {
        $pythonExecutable = '/home/penandprocess/virtualenvs/myenv/bin/python';
$python_script = "/home/penandprocess/public_html/vt.penandprocess.com/quiz/main.py";
        $command = escapeshellcmd("$pythonExecutable $python_script") . " 2>&1";
        $output = shell_exec($command);
        $output_array = explode(",", $output);
        $question = trim($output_array[0]);
        $question = str_replace(["'", "("], "", $question);
        $_SESSION['question'] = $question;

        header("Location: quiz_start.php");
        exit();
    } else {
        echo "<p>The quiz is now complete. You may retake the quiz or click on Next to proceed.</p>";
        file_put_contents($count_file, '0');
        echo '<form id="quiz" action="quiz_start.php" method="post">
        <div class="buttons-container">
            <div class="button-wrapper">
                <a href="home.php" class="back-button"><span class="back-arrow"></span>Back</a>
                <button type="submit" name="reset_quiz" onclick="showLoader()">Retake Quiz</button>
            </div>
        </div>
        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>
    </form>';
    }
    ?>
    

    <script>
       

        function showLoader() {
            document.getElementById("loaderContainer").style.display = "block";
            const buttons = document.querySelectorAll('input[type="submit"]');
            buttons.forEach(button => {
                button.classList.add('loading-button');
                button.value = 'Loading...';
            });
        }
    </script>
</div>
</body>
</html>