<?php

/**
 * AIService - Integração com OpenAI
 */
class AIService {
    private $apiKey;

    public function __construct() {
        $this->apiKey = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';
    }

    public function generateWelcomeMessage($leadName, $planName) {
        if (empty($this->apiKey)) {
            return "Olá $leadName, obrigado por escolher a Memora Movie! Recebemos seu interesse no plano $planName e entraremos em contato em breve.";
        }

        $endpoint = 'https://api.openai.com/v1/chat/completions';
        
        $prompt = "
        Você é o assistente de comunicação da 'Memora Movie', uma empresa que transforma arquivos digitais (fotos/vídeos) em filmes cinematográficos de memórias.
        
        Escreva um e-mail curto, extremamente elegante e acolhedor para um novo cliente chamado '$leadName' que acabou de selecionar o plano '$planName'.
        
        Objetivo do email:
        1. Agradecer o interesse.
        2. Explicar brevemente em 1 frase poética o que a Memora faz (transforma momentos em cinema).
        3. Dizer que um 'Diretor de Memórias' entrará em contato via WhatsApp em breve para explicar os próximos passos.
        4. O tom deve ser 'premium', 'emocionante' e 'profissional'.
        
        Retorne APENAS o corpo do email (HTML simples com <p> e <br>), sem assunto e sem saudações extras fora do corpo. Assine como 'Equipe Memora Movie'.
        ";

        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um copywriter especialista em marcas de luxo e cinema.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);

        return $json['choices'][0]['message']['content'] ?? "Olá $leadName, recebemos seu contato com carinho. Em breve falaremos mais sobre o plano $planName.";
    }
}
