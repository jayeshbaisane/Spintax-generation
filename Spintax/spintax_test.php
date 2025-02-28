<?php
// Function to process spintax and generate a random variation
function spinText($text) {
    // Match all spintax patterns (e.g., {option1|option2|option3})
    return preg_replace_callback(
        '/\{([^{}]*)\}/',
        function ($matches) {
            // Split the options by the pipe character
            $options = explode('|', $matches[1]);
            // Randomly select one of the options
            return $options[array_rand($options)];
        },
        $text
    );
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputText = $_POST['text'];
    $numVariations = (int)$_POST['variations']; // Number of variations
    $spunTexts = [];

    // Generate multiple spun text variations
    for ($i = 0; $i < $numVariations; $i++) {
        $spunTexts[] = spinText($inputText);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spintax Tool with Comment Box</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }
        textarea {
            width: 97%;
            height: 100px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: none;
        }
        input[type="number"] {
            width: 97%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .comment-box {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: left;
        }
        .comment {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .comment p {
            margin: 0;
            flex: 1;
        }
        .copy-button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        .copy-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Spintax Tool with multiple Generation</h1>
        <form method="POST">
            <textarea name="text" placeholder="Enter spintax text (e.g., {Hello|Hi} {world|there}!)" required><?php echo isset($inputText) ? htmlspecialchars($inputText) : ''; ?></textarea>
            <input type="number" name="variations" placeholder="Number of variations" min="1" max="100" required>
            <button type="submit">Generate Variations</button>
        </form>

        <?php if (isset($spunTexts)): ?>
            <div class="comment-box">
                <h3>Generated Variations:</h3>
                <?php foreach ($spunTexts as $index => $spunText): ?>
                    <div class="comment">
                        <p><?php echo htmlspecialchars($spunText); ?></p>
                        <button class="copy-button" onclick="copyToClipboard('<?php echo addslashes($spunText); ?>')">Copy</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Function to copy text to clipboard
        function copyToClipboard(text) {
            // Create a temporary textarea element
            const tempTextarea = document.createElement('textarea');
            tempTextarea.value = text;
            document.body.appendChild(tempTextarea);

            // Select and copy the text
            tempTextarea.select();
            document.execCommand('copy');

            // Remove the temporary textarea
            document.body.removeChild(tempTextarea);

            // Alert the user (optional)
            alert('Copied to clipboard: ' + text);
        }
    </script>
</body>
</html>