Плагин "Blacklist" (версия 1.0.2) для LiveStreet 1.0.3


ОПИСАНИЕ

Проверка E-Mail и IP пользователей на наличие в базах спамеров.

Настройка плагина осуществляется редактированием файла "/plugins/blacklist/config/config.php".

Поддерживаемые директивы:
1) $config['check_mail'] - Проверять e-mail по базам. По умолчанию включено (true).

2) $config['check_mail_limit'] - Порог для срабатывания проверки e-mail (не менее указанного значения).

3) $config['check_ip'] - Проверять IP по базам. По умолчанию включено (true).

4) $config['check_ip_limit'] - Порог для срабатывания проверки IP (не менее указанного значения).

5) $config['check_ip_exact'] - Строгая проверка IP (e-mail и IP должны быть в базе одновременно). По умолчанию отлючено (false).

6) $config['use_stopforumspam_org'] - Использовать базу сайта stopforumspam.org. По умолчанию включено (true).

7) $config['use_botscout_com'] - Использовать базу сайта botscout.com. По умолчанию включено (true).

8) $config['key_botscout_com'] - Ключ для сайта botscout.com (http://botscout.com/getkey.htm).

9) $config['use_fspamlist_com'] - Использовать базу сайта fspamlist.com. По умолчанию включено (true).

10) $config['key_fspamlist_com'] - Ключ для сайта fspamlist.com (http://fspamlist.com/index.php?c=register).

11) $config['check_authorization'] - Проверять e-mail при авторизации. По умолчанию включено (true).

12) $config['whitelist_domains'] - Белый список доменов (e-mail с этих доменов считаются доверенными и не проверяются).

13) $config['blacklist_domains'] - Черный список доменов (e-mail с этих доменов запрещены).

14) $config['whitelist_users_name'] - Белый список пользователей (логины). Проверяется только при авторизации.

15) $config['whitelist_users_mail'] - Белый список пользователей (e-mail).

16) $config['whitelist_users_ip'] - Белый список пользователей (IP-адреса).

17) $config['blacklist_users_name'] - Черный список пользователей (логины). Проверяется только при авторизации.

18) $config['blacklist_users_mail'] - Черный список пользователей (e-mail).

19) $config['blacklist_users_ip'] - Черный список пользователей (IP-адреса).



УСТАНОВКА

1. Скопировать плагин в каталог /plugins/
2. Через панель управления плагинами (/admin/plugins/) запустить его активацию.



ИЗМЕНЕНИЯ:
1.0.2 (17.10.2014):
- Добавлена возможность запрета всех доменов (маска '*' в черном списке доменов).
- Исправлена неточность в работе с базой fspamlist.com
- Добавлена возможность отключения проверки e-mail.
- Добавлено исключение проверки администраторов на наличие в черном списке при авторизации.
- Добавлены параметры конфигурации:
$config['check_mail'] - Проверять e-mail по базам.
$config['whitelist_users_name'] - Белый список пользователей (логины). Проверяется только при авторизации.
$config['whitelist_users_mail'] - Белый список пользователей (e-mail).
$config['whitelist_users_ip'] - Белый список пользователей (IP-адреса).
$config['blacklist_users_name'] - Черный список пользователей (логины). Проверяется только при авторизации.
$config['blacklist_users_mail'] - Черный список пользователей (e-mail).
$config['blacklist_users_ip'] - Черный список пользователей (IP-адреса).
$config['check_mail_limit'] - Порог для срабатывания проверки e-mail (не менее указанного значения).
$config['check_ip_limit'] - Порог для срабатывания проверки IP (не менее указанного значения).

1.0.1 (19.09.2014):
- Функционал вынесен в отдельный класс.
- Добавлены параметры:
$config['whitelist_domains'] - Белый список доменов (e-mail с этих доменов считаются доверенными и не проверяются).
$config['blacklist_domains'] - Черный список доменов (e-mail с этих доменов запрещены).
$config['check_ip'] - Дополнительно проверять IP.
$config['check_ip_exact'] - Строгая проверка IP (e-mail и IP должны быть в базе одновременно).



АВТОР
Александр Вереник

САЙТ 
https://github.com/wasja1982/livestreet_blacklist
