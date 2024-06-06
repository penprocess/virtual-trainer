<!DOCTYPE html>
<html>
<head>
    <title>Quiz Application</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #fff;
            font-family: Arial, sans-serif;
        }

        form {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
        }

        button[type="button"] {
            padding: 15px 30px;
            width: 200px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 10px;
            margin-bottom: 100px;
        }

        button {
            position: relative;
        }

        .loader-container {
            display: none;
            align-items: center;
            justify-content: center;
            position: fixed;
            bottom: -10%;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 999;
        }

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        button[name="ask_quiz"]:hover::after,
        button[name="take_quiz"]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: -140px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            background-color: rgba(0, 0, 0, 0.4);
            color: #fff;
            padding: 5px;
            border-radius: 3px;
            transition: opacity 0.3s, visibility 0.3s;
            opacity: 1;
            visibility: visible;
        }

        button[type="button"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<form id="queryForm">
    <button type="button" name="ask_quiz" data-tooltip="Click to ask any question about the previously learned lesson." onclick="redirectTo('quest.php')">Ask a Question</button>
    <div style="height:160px;width: 2px; background-color: #ccc;margin: 0 20px; margin-bottom: 90px;"></div>
    <button type="button" name="take_quiz" data-tooltip="Attempt a quiz to test your knowledge of previously learned material. Note: Taking this quiz won't affect your final scores." onclick="redirectTo('quiz_start.php')">Take Quiz</button>
    <div class="loader-container" id="loaderContainer">
        <div class="loader"></div>
    </div>
</form>

<script>
    function showLoading() {
        document.getElementById('loaderContainer').style.display = 'flex';
    }

    function redirectTo(url) {
        showLoading();
        setTimeout(function() {
            window.location.href = url;
        }, 1000); // Delay for loader effect, can be adjusted or removed
    }
</script>

</body>
</html>