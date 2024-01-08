<!-- archivo actual.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asistente de IA</title>
</head>
<body>
    <section class="chatbot">
        <h2>Asistente IA</h2>
        <form method="post">
            <label for="prompt">Pregunte lo que quiera mi estimado señor...</label>
            <input type="text" id="prompt" name="prompt" required>
            <input type="submit" value="Enviar">
        </form>

        <?php
        // Iniciar la sesión para poder acceder a $_SESSION
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $api_key = "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";
            $prompt = $_POST['prompt'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api_key,
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                "model" => "gpt-3.5-turbo",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "You are a helpful assistant. Who gives information clearly and as concise as possible, and format it in this structure: 
                            Title: Title: 
                            Content: Content:"
                    ],
                    [
                        "role" => "user",
                        "content" => $prompt
                    ]
                ]
            ]));

            $response = curl_exec($ch);
            $ai_response = json_decode($response);

            if (isset($ai_response->choices[0]->message->content)) {
                $assistant_content = $ai_response->choices[0]->message->content;

                // Almacenar en una variable de sesión
                $_SESSION['assistant_content'] = $assistant_content;

                // Reemplazar los saltos de línea con etiquetas <br> para formatear los párrafos
                $formatted_content = nl2br($assistant_content);

                echo $formatted_content;
            } else {
                echo "No se encontró contenido de asistente en la respuesta.";
            }
            curl_close($ch);
        }

        // Verificar si existe el contenido del asistente en la sesión y mostrarlo
        if (isset($_SESSION['assistant_content'])) {
            echo $_SESSION['assistant_content'];
        }
        ?>
    </section>
</body>
</html>
