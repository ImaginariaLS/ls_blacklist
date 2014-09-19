<?php
/**
 * Blacklist - проверка E-Mail пользователей на наличие в базах спамеров.
 *
 * Версия:	1.0.0
 * Автор:	Александр Вереник
 * Профиль:	http://livestreet.ru/profile/Wasja/
 * GitHub:	https://github.com/wasja1982/livestreet_blacklist
 *
 **/

class PluginBlacklist_ModuleBlacklist extends Module {
    public function Init () {
    }

    public function check_whitelist_domains($sMail) {
        $aMail = explode("@", $sMail);
        $sDomain = (count($aMail) > 1 ? $aMail[1] : '');
        return in_array(strtolower($sDomain), Config::Get('plugin.blacklist.whitelist_domains'));
    }

    public function check_blacklist_domains($sMail) {
        $aMail = explode("@", $sMail);
        $sDomain = (count($aMail) > 1 ? $aMail[1] : '');
        return in_array(strtolower($sDomain), Config::Get('plugin.blacklist.blacklist_domains'));
    }

    public function check_stopforumspam_org($sMail, $sIp) {
        $aParams = array(
            'f' => 'json',
            'email' => $sMail,
        );
        $bCheckIp = (Config::Get('plugin.blacklist.check_ip') && $sIp && $sIp !== '127.0.0.1');
        if ($bCheckIp) {
            $aParams['ip'] = $sIp;
        }
        $sUrl = 'http://api.stopforumspam.org/api' . '?' . urldecode(http_build_query($aParams));
        $sAnswer = @file_get_contents($sUrl);
        $aInfo = json_decode($sAnswer, true);
        if (isset($aInfo['success']) && $aInfo['success']) {
            $bMail = false;
            if (isset($aInfo['email']) && isset($aInfo['email']['appears'])) {
                $bMail = ($aInfo['email']['appears'] ? true : false);
            }
            if ($bCheckIp) {
                $bIp = false;
                if (isset($aInfo['ip']) && isset($aInfo['ip']['appears'])) {
                    $bIp = ($aInfo['ip']['appears'] ? true : false);
                }
                return (Config::Get('plugin.blacklist.check_ip_exact') ? ($bMail && $bIp) : ($bMail || $bIp));
            } else {
                return $bMail;
            }
        }
        return false;
    }

    public function check_botscout_com($sMail, $sIp) {
        $aParams = array(
            'key' => Config::Get('plugin.blacklist.key_botscout_com'),
            'mail' => $sMail,
        );
        $bCheckIp = (Config::Get('plugin.blacklist.check_ip') && $sIp && $sIp !== '127.0.0.1');
        if ($bCheckIp) {
            $aParams['ip'] = $sIp;
            $aParams['multi'] = true;
        }
        $sUrl = 'http://botscout.com/test/' . '?' . urldecode(http_build_query($aParams));
        $sAnswer = @file_get_contents($sUrl);
        if ($sAnswer) {
            $aAnswer = explode('|', $sAnswer);
            if (count($aAnswer) > 1 && $aAnswer[0] === 'Y') {
                if ($bCheckIp && $aAnswer[1] === 'MULTI') {
                    $bMail = false;
                    $bIp = false;
                    for ($i = 2; $i < count($aAnswer); $i += 2) {
                        if (isset($aAnswer[$i]) && isset($aAnswer[$i+1])) {
                            if ($aAnswer[$i] == 'MAIL' && $aAnswer[$i+1] > 0) {
                                $bMail = true;
                            } elseif ($aAnswer[$i] == 'IP' && $aAnswer[$i+1] > 0) {
                                $bIp = true;
                            }
                        }
                    }
                    return (Config::Get('plugin.blacklist.check_ip_exact') ? ($bMail && $bIp) : ($bMail || $bIp));
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    public function check_fspamlist_com($sMail, $sIp) {
        $aParams = array(
            'json' => true,
            'key' => Config::Get('plugin.blacklist.key_fspamlist_com'),
            'spammer' => $sMail,
        );
        $bCheckIp = (Config::Get('plugin.blacklist.check_ip') && $sIp && $sIp !== '127.0.0.1');
        if ($bCheckIp) {
            $aParams['spammer'] = $sMail . ',' . $sIp;
        }
        $sUrl = 'http://www.fspamlist.com/api.php' . '?' . urldecode(http_build_query($aParams));
        $sAnswer = @file_get_contents($sUrl);
        $aInfo = json_decode($sAnswer, true);
        if (count($aInfo)) {
            $bMail = false;
            $bIp = false;
            foreach ($aInfo as $aItem) {
                if (isset($aItem['spammer'])) {
                    if ($aItem['spammer'] == $sMail) {
                        $bMail = ((isset($aItem['isspammer']) && $aItem['isspammer']) ? true : false);
                    } elseif ($aItem['spammer'] == $sIp) {
                        $bIp = ((isset($aItem['isspammer']) && $aItem['isspammer']) ? true : false);
                    }
                }
            }
            return (Config::Get('plugin.blacklist.check_ip_exact') ? ($bMail && $bIp) : ($bMail || $bIp));
        }
        return false;
    }

    public function blackMail($sMail) {
        if (empty($sMail)) {
            return false;
        }
        if ($this->check_whitelist_domains($sMail)) {
            return false;
        }
        if ($this->check_blacklist_domains($sMail)) {
            return true;
        }
        $sIp = func_getIp();
        $bResult = false;
        if (Config::Get('plugin.blacklist.use_stopforumspam_org')) {
            $bResult = $this->check_stopforumspam_org($sMail, $sIp);
        }
        if (!$bResult && Config::Get('plugin.blacklist.use_botscout_com')) {
            $bResult = $this->check_botscout_com($sMail, $sIp);
        }
        if (!$bResult && Config::Get('plugin.blacklist.use_fspamlist_com')) {
            $bResult = $this->check_fspamlist_com($sMail, $sIp);
        }
        return $bResult;
    }
}
?>