<?php

/**
 * SimpleSMTP - Leve classe para envio de e-mails via SMTP Sockets
 */
class SimpleSMTP {
    private $host;
    private $user;
    private $pass;
    private $port;
    private $secure;

    public function __construct() {
        $this->host = defined('SMTP_HOST') ? SMTP_HOST : '';
        $this->user = defined('SMTP_USER') ? SMTP_USER : '';
        $this->pass = defined('SMTP_PASS') ? SMTP_PASS : '';
        $this->port = defined('SMTP_PORT') ? SMTP_PORT : 465;
        $this->secure = defined('SMTP_SECURE') ? SMTP_SECURE : 'ssl';
    }

    public function send($to, $subject, $body, $fromName = 'Memora Movie') {
        if (empty($this->host) || empty($this->user)) {
            error_log("SMTP Credentials missing.");
            return false;
        }

        try {
            $socket = ($this->secure == 'ssl' ? 'ssl://' : '') . $this->host;
            $conn = fsockopen($socket, $this->port, $errno, $errstr, 15);

            if (!$conn) {
                throw new Exception("Could not connect to SMTP host: $errstr");
            }

            $this->serverCmd($conn, "EHLO " . $this->host);
            $this->serverCmd($conn, "AUTH LOGIN");
            $this->serverCmd($conn, base64_encode($this->user));
            $this->serverCmd($conn, base64_encode($this->pass));

            $this->serverCmd($conn, "MAIL FROM: <{$this->user}>");
            $this->serverCmd($conn, "RCPT TO: <$to>");
            $this->serverCmd($conn, "DATA");

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "To: $to\r\n";
            $headers .= "From: $fromName <{$this->user}>\r\n";
            $headers .= "Subject: $subject\r\n";
            $headers .= "Date: " . date("r") . "\r\n";

            fwrite($conn, "$headers\r\n$body\r\n.\r\n");
            $result = fgets($conn, 512);

            $this->serverCmd($conn, "QUIT");
            fclose($conn);

            return true;
        } catch (Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }

    private function serverCmd($conn, $cmd) {
        fwrite($conn, $cmd . "\r\n");
        $response = fgets($conn, 512);
        // Pode adicionar verificação de código de resposta aqui se necessário (ex: 250, 235, etc)
        return $response;
    }
}
