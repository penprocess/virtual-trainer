<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #ffffff; }
        .container { width: 600px; height: 400px; padding: 40px; border-radius: 10px; background-color: #fff; }
        form { max-width: 100%; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); margin-bottom: 20px; display: flex; flex-direction: column; align-items: center; margin-left: 10px; }
        input { width: 100%; padding: 8px; margin-bottom: 16px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; height: 50px; }
        button { padding: 3px 10px; background-color: #007fff; color: #fff; text-decoration: none; border-radius: 5px; transition: background-color 0.3s, transform 0.2s; font-size: 15px; border: none; cursor: pointer; outline: none; position: relative; margin-left: 10px; }
        .buttons-container { display: flex; justify-content: flex-end; width: 100%; margin-bottom: 10px; }
        .button-wrapper { display: flex; }
        .back-arrow::before { content: "\00ab"; margin-right: 5px; font-family: "Font Awesome 5 Free"; }
        .back-button { display: flex; padding: 3px 10px; background-color: grey; color: #fff; text-decoration: none; border-radius: 5px; transition: background-color 0.3s, transform 0.2s; font-size: 15px; border: none; cursor: pointer; outline: none; position: relative; margin-left: 10px; }
        p { font-size: 18px; color: #000000; margin-top: 10px; }
        p.text-danger { color: #ff0000; margin-top: 10px; }
        .response-container { max-width: 100%; overflow-y: scroll; padding: 15px; background-color: rgba(230, 230, 230, 0.5); border: 1px solid #6b4b6b; border-radius: 4px; width: 100%; margin-top: 10px; }
        p.response { margin: 0px; color: #007fff; font-weight: bold; }
        .back-button:hover, button:hover { background-color: #808085; transform: scale(1.05); }
        .back-button:active, button:active { transform: scale(0.95); }
        .loading-button { background-color: #cccccc; color: #666666; pointer-events: none; }
        .loader-container { text-align: center; margin-top: 20px; display: none; }
        .loader { border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-left: auto; margin-right: auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); }
}
    </style>
</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<div class="container">
    <form action="" method="post" enctype="multipart/form-data" id="queryForm">
        <label for="user_input"></label>
        <input type="text" name="user_input" placeholder="Enter your query" value="<?php echo isset($_POST['user_input']) ? htmlspecialchars($_POST['user_input']) : ''; ?>"><br>
        <div class="buttons-container">
            <div class="button-wrapper">
                <a href="home.php" class="back-button"><span class="back-arrow"></span>Back</a>
                <button type="submit" style="background:#007bff" onclick="showLoader()">Generate</button>
            </div>
        </div>
        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_input = isset($_POST["user_input"]) ? $_POST["user_input"] : '';
            $user_input = escapeshellarg($user_input);
            // path to python exe
            $pythonExecutable = '/home/penandprocess/virtualenvs/myenv/bin/python';
            // path to the python file in cpanel
$pythonScript = "/home/penandprocess/public_html/vt.penandprocess.com/quiz/qna.py";
            $command = "$pythonExecutable $pythonScript $user_input";
            
            $descriptorspec = array(
                0 => array("pipe", "r"),  // stdin
                1 => array("pipe", "w"),  // stdout
                2 => array("pipe", "w")   // stderr
            );
            
            $process = proc_open($command, $descriptorspec, $pipes, null, null);

            if (is_resource($process)) {
                $output = stream_get_contents($pipes[1]);
                $error_output = stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                $return_value = proc_close($process);

                echo '<div class="response-container">';
                echo '<p class="response">Response:</p>';
                if ($return_value !== 0) {
                    echo '<p class="text-danger">Error executing the command:</p>';
                    echo '<p class="text-danger">' . htmlspecialchars($error_output) . '</p>';
                } else {
                    echo '<p>' . nl2br(htmlspecialchars($output)) . '</p>';
                }
                echo '</div>';
            } else {
                echo '<div class="response-container">';
                echo '<p class="text-danger">Failed to open process</p>';
                echo '</div>';
            }
        }
        ?>
    </form>
    <script>
        function showLoader() {
            document.getElementById("loaderContainer").style.display = "block";
            const buttons = document.querySelectorAll('button[type="submit"]');
            buttons.forEach(button => {
                button.classList.add('loading-button');
                button.disabled = true; // Disable the button while loading
                button.innerHTML = '<span style="color: #ffffff;">Generating Response...</span>'; // Change the button text with color
            });
            // Trigger form submission after showing loader
            document.getElementById("queryForm").submit();
        }
    </script>
</div>

</body>
</html>