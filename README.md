# Laravel-Socialite - GoodGame.ru
Расширение для Laravel Socialite - Авторизация через GoodGame.ru

# Установка / Install
Установите расширение:

```# composer require ivandanilov/laravel-socialite-goodgame```

Зарегистрируйте расширение в файле **app/Providers/EventServiceProvider.php**:
```
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        'SocialiteProviders\Twitter\TwitterExtendSocialite@handle'
    ],
];
```
Добавьте конфигурацию в файл настроек **config/services.php**:
```
'goodgame' => [
    'client_id' => env('GOODGAME_KEY'),
    'client_secret' => env('GOODGAME_SECRET'),
    'redirect' => env('GOODGAME_REDIRECT_URI'),
]
```
В файле .env введите данные для работы с GoodGame OAuth:
```
GOODGAME_KEY="Созданное вами ID приложения"
GOODGAME_SECRET="Созданный вами ключ"
GOODGAME_REDIRECT_URI="Страница для возврата (Ваш обработчик)"
```
# Больше информации
Документация GoodGame: https://github.com/GoodGame/API/blob/master/Streams/v2/authentication.md
