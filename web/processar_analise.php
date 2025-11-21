<?php
//Configuração do destinatário
$email_destino = "forny918@gmail.com";

//Verificar se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    //Função para limpar e sanitizar dados
    function limpar($dado) {
        return htmlspecialchars(strip_tags(trim($dado)));
    }


    // 1. COLETAR DADOS PESSOAIS
    $nome = limpar($_POST['Nome_Completo'] ?? ''); //o ponto de interrogação serve para evitar erros caso o índice não exista
    $cargo = limpar($_POST['Cargo'] ?? ''); //o limpar é uma função criada para evitar ataques de XSS (Cross-Site Scripting - injeção de código malicioso)
    $email_usuario = limpar($_POST['Email'] ?? '');
    $telefone = limpar($_POST['Telefone'] ?? '');
    $empresa = limpar($_POST['Empresa'] ?? '');
    $tempo_empresa = limpar($_POST['Tempo_na_Empresa'] ?? '');

    // 2. COLETAR RESPOSTAS DAS QUESTÕES (1-5)
    $q1 = intval($_POST['Q1_Fluxo_Definido'] ?? 0); 
    $q2 = intval($_POST['Q2_Procedimentos'] ?? 0);
    $q3 = intval($_POST['Q3_Integracao_Areas'] ?? 0);
    $q4 = intval($_POST['Q4_Tempo_Monitorado'] ?? 0);
    $q5 = intval($_POST['Q5_Sistema_Atende'] ?? 0);
    $q6 = intval($_POST['Q6_Integracao_Sistema'] ?? 0);
    $q7 = intval($_POST['Q7_Controles_Estoque'] ?? 0);
    $q8 = intval($_POST['Q8_Indicadores'] ?? 0);
    $q9 = intval($_POST['Q9_Clareza_Lideres'] ?? 0);
    $q10 = intval($_POST['Q10_Engajamento'] ?? 0);
    $q11 = intval($_POST['Q11_Atuacao_Preventiva'] ?? 0);
    $q12 = intval($_POST['Q12_Valorizacao_Logistica'] ?? 0);
    $q13 = intval($_POST['Q13_Investimentos'] ?? 0);
    $q14 = intval($_POST['Q14_Ferramentas_Digitais'] ?? 0);
    $q15 = intval($_POST['Q15_Abertura_Inovacao'] ?? 0);

 
    // 3. CALCULAR PONTUAÇÃO TOTAL
    $pontuacao_total = $q1 + $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + 
                       $q9 + $q10 + $q11 + $q12 + $q13 + $q14 + $q15;
    $pontuacao_maxima = 75; // 15 questões × 5 pontos
    $percentual = round(($pontuacao_total / $pontuacao_maxima) * 100, 1);

    // 4. DETERMINAR NÍVEL DE MATURIDADE
    if ($pontuacao_total >= 61) {
        $nivel = "Nível 5: Otimizada";
    } elseif ($pontuacao_total >= 46) {
        $nivel = "Nível 4: Gerenciada";
    } elseif ($pontuacao_total >= 31) {
        $nivel = "Nível 3: Definida";
    } elseif ($pontuacao_total >= 16) {
        $nivel = "Nível 2: Repetível";
    } else {
        $nivel = "Nível 1: Inicial";
    }


    // 5. CALCULAR PONTUAÇÃO POR DIMENSÃO
    $fluxo_materiais = $q1 + $q2 + $q3 + $q4; // Max: 20
    $sistema_controles = $q5 + $q6 + $q7 + $q8; // Max: 20
    $lideranca_cultura = $q9 + $q10 + $q11 + $q12; // Max: 20
    $tecnologia_inovacao = $q13 + $q14 + $q15; // Max: 15

    // 6. COLETAR PERCEPÇÕES
    $desafios = limpar($_POST['Desafios_Principais'] ?? '');
    $melhorias = limpar($_POST['Melhorias_Urgentes'] ?? '');
    $observacoes = limpar($_POST['Observacoes_Adicionais'] ?? 'Não informado');


    // 7. MONTAR CORPO DO E-MAIL
    $assunto = "🎯 Novo Diagnóstico Logístico - {$empresa}";
    
    $mensagem = "
===========================================================
📊 DIAGNÓSTICO LOGÍSTICO - JGM4 CONSULTORIA
===========================================================

Data/Hora: " . date('d/m/Y H:i:s') . "

-----------------------------------------------------------
👤 DADOS DO RESPONDENTE
-----------------------------------------------------------
Nome: {$nome}
Cargo: {$cargo}
Empresa: {$empresa}
E-mail: {$email_usuario}
Telefone: {$telefone}
Tempo na empresa: {$tempo_empresa}

-----------------------------------------------------------
🏆 ÍNDICE DE MATURIDADE LOGÍSTICA (IML)
-----------------------------------------------------------
Pontuação Total: {$pontuacao_total} / {$pontuacao_maxima} pontos
Percentual: {$percentual}%
Classificação: {$nivel}

-----------------------------------------------------------
📊 PONTUAÇÃO POR DIMENSÃO
-----------------------------------------------------------
1. Fluxo de Materiais: {$fluxo_materiais}/20 (" . round(($fluxo_materiais/20)*100, 1) . "%)
2. Sistema e Controles: {$sistema_controles}/20 (" . round(($sistema_controles/20)*100, 1) . "%)
3. Liderança e Cultura: {$lideranca_cultura}/20 (" . round(($lideranca_cultura/20)*100, 1) . "%)
4. Tecnologia e Inovação: {$tecnologia_inovacao}/15 (" . round(($tecnologia_inovacao/15)*100, 1) . "%)

-----------------------------------------------------------
📝 RESPOSTAS DETALHADAS
-----------------------------------------------------------

FLUXO DE MATERIAIS:
Q1 - Fluxo bem definido e seguido: {$q1}/5
Q2 - Procedimentos padronizados: {$q2}/5
Q3 - Integração entre áreas: {$q3}/5
Q4 - Tempo monitorado e otimizado: {$q4}/5

SISTEMA E CONTROLES:
Q5 - Sistema atende necessidades: {$q5}/5
Q6 - Integração do sistema: {$q6}/5
Q7 - Controles de estoque confiáveis: {$q7}/5
Q8 - Indicadores acompanhados: {$q8}/5

LIDERANÇA E CULTURA:
Q9 - Clareza de metas e responsabilidades: {$q9}/5
Q10 - Engajamento da equipe: {$q10}/5
Q11 - Atuação preventiva: {$q11}/5
Q12 - Valorização da logística: {$q12}/5

TECNOLOGIA E INOVAÇÃO:
Q13 - Investimentos regulares: {$q13}/5
Q14 - Ferramentas digitais: {$q14}/5
Q15 - Abertura para inovação: {$q15}/5

-----------------------------------------------------------
💡 PERCEPÇÕES DO RESPONDENTE
-----------------------------------------------------------

PRINCIPAIS DESAFIOS:
{$desafios}

MELHORIAS URGENTES:
{$melhorias}

OBSERVAÇÕES ADICIONAIS:
{$observacoes}

===========================================================
FIM DO RELATÓRIO
===========================================================
";

    // 8. CONFIGURAR CABEÇALHOS DO E-MAIL
    $headers = "From:{$email_usuario}\r\n";
    $headers .= "Reply-To: {$email_destino} \r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

   
    // 9. ENVIAR E-MAIL
    if (mail($email_destino, $assunto, $mensagem, $headers)) {
        // Redirecionar para página de agradecimento
        header("Location: obrigado.html");
        exit();
    } else {
        // Caso haja erro no envio
        echo "<!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <title>Erro - JGM4</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; text-align: center; }
                .erro { background: #fee; border: 2px solid #c00; padding: 20px; border-radius: 8px; max-width: 500px; margin: 0 auto; }
            </style>
        </head>
        <body>
            <div class='erro'>
                <h2>❌ Erro ao enviar formulário</h2>
                <p>Ocorreu um problema ao processar seu diagnóstico.</p>
                <p>Por favor, entre em contato diretamente pelo e-mail: <strong>{$email_destino}</strong></p>
                <br>
                <a href='analise.html'>← Voltar ao formulário</a>
            </div>
        </body>
        </html>";
    }

} else {
    // Acesso direto ao script sem POST
    header("Location: analise.html");
    exit();
}
?>