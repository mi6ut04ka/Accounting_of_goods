<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение email</title>
</head>
<body>
<h2>Здравствуйте, {{ $user->name }}!</h2>
<p>Вы зарегистрировались на нашем сайте. Пожалуйста, подтвердите свой email, нажав на кнопку ниже:</p>
<a href="{{ $verificationUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
    Подтвердить email
</a>
<p>Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.</p>
</body>
</html>
