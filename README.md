# kch/smsapi-bundle
Symfony 2 Bundle for SMSApi PHP Client library.

Symfony 2 Bundle dla biblioteki SMSApi PHP Client.

## Installation:
`composer require kch/smsapi-bundle`

## Configuration - config.yml:
```
kch_sms_api:
    clients:
        default:
            client_login: TEST
            client_password_hash: TEST
```

## Usage - sending one SMS:
Based on original library code: [https://github.com/smsapi/smsapi-php-client/wiki/Examples](https://github.com/smsapi/smsapi-php-client/wiki/Examples)
```
$smsFactory = $this->get('kch_sms_api.sms_factory.default');

try {
    $actionSend = $smsFactory->actionSend();

    $actionSend->setTo('600xxxxxx');
    $actionSend->setText('Hello World!!');
    $actionSend->setSender('Info'); //Pole nadawcy, lub typ wiadomości: 'ECO', '2Way'

    $response = $actionSend->execute();

    foreach ($response->getList() as $status) {
        echo $status->getNumber() . ' ' . $status->getPoints() . ' ' . $status->getStatus();
    }
} catch (SmsapiException $exception) {
    echo 'ERROR: ' . $exception->getMessage();
}
```