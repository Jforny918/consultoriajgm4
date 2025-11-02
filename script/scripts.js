// Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        function closeMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.remove('active');
        }

        function submitQuiz(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            }
            
            // Calcular IML (Índice de Maturidade Logística)
            const pontuacoes = [
                parseInt(data.fluxo_definido) || 0,
                parseInt(data.procedimentos) || 0,
                parseInt(data.integracao_areas) || 0,
                parseInt(data.tempo_monitorado) || 0,
                parseInt(data.sistema_atende) || 0,
                parseInt(data.integracao_sistema) || 0,
                parseInt(data.controles_estoque) || 0,
                parseInt(data.indicadores) || 0,
                parseInt(data.clareza_lideres) || 0,
                parseInt(data.engajamento) || 0,
                parseInt(data.atuacao_preventiva) || 0,
                parseInt(data.valorizacao_logistica) || 0,
                parseInt(data.investimentos) || 0,
                parseInt(data.ferramentas_digitais) || 0,
                parseInt(data.abertura_inovacao) || 0
            ];
            
            const soma = pontuacoes.reduce((acc, val) => acc + val, 0);
            const iml = Math.round((soma / 75) * 100); // 15 perguntas * 5 pontos = 75 máximo
            
            let nivel, descricao, cor, recomendacoes;
            
            if (iml <= 40) {
                nivel = 'Inicial';
                cor = '#ff3333';
                descricao = 'Processos informais, controles manuais, foco operacional.';
                recomendacoes = [
                    'Implementar procedimentos operacionais padronizados (POPs)',
                    'Estabelecer controles básicos de estoque e movimentação',
                    'Definir indicadores-chave de desempenho (KPIs) prioritários',
                    'Capacitar lideranças em gestão logística básica'
                ];
            } else if (iml <= 65) {
                nivel = 'Intermediário';
                cor = '#ffeb3b';
                descricao = 'Alguns controles e procedimentos estabelecidos, melhorias pontuais sendo implementadas.';
                recomendacoes = [
                    'Padronizar processos em todas as áreas logísticas',
                    'Avaliar e implementar sistema de gestão (WMS/TMS)',
                    'Estruturar programa de melhoria contínua',
                    'Integrar sistemas entre departamentos',
                    'Desenvolver cultura de análise de dados'
                ];
            } else if (iml <= 85) {
                nivel = 'Avançado';
                cor = '#1e90ff';
                descricao = 'Processos padronizados, integração parcial, forte foco em eficiência operacional.';
                recomendacoes = [
                    'Otimizar processos existentes com foco em produtividade',
                    'Implementar automações e tecnologias avançadas',
                    'Desenvolver indicadores estratégicos além dos operacionais',
                    'Fortalecer integração total entre sistemas',
                    'Estabelecer benchmarking com melhores práticas do mercado'
                ];
            } else {
                nivel = 'Excelência';
                cor = '#28a745';
                descricao = 'Cultura logística consolidada, tecnologia totalmente integrada, visão estratégica estabelecida.';
                recomendacoes = [
                    'Manter programa de inovação contínua',
                    'Explorar tecnologias emergentes (IA, IoT, Big Data)',
                    'Compartilhar melhores práticas internamente',
                    'Desenvolver parcerias estratégicas na cadeia de suprimentos',
                    'Atuar como referência de mercado em logística'
                ];
            }
            
            console.log('Dados do questionário:', data);
            console.log('IML calculado:', iml);
            
            // Exibir resultado
            const resultadoDiv = document.getElementById('resultado');
            const conteudoDiv = document.getElementById('resultado-conteudo');
            
            conteudoDiv.innerHTML = `
                <div style="text-align: center; margin-bottom: var(--space-32);">
                    <div style="font-size: 4rem; font-weight: 700; color: ${cor}; margin-bottom: var(--space-16);">${iml}</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: ${cor}; margin-bottom: var(--space-8);">Nível ${nivel}</div>
                    <p style="color: var(--color-text-light); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">${descricao}</p>
                </div>
                
                <div style="background: rgba(0, 0, 0, 0.3); padding: var(--space-24); border-radius: var(--radius-base); margin-bottom: var(--space-24);">
                    <h4 style="color: var(--color-text); margin-bottom: var(--space-16); font-size: 1.2rem;">📋 Recomendações Prioritárias:</h4>
                    <ul style="color: var(--color-text-light); line-height: 1.8; margin: 0; padding-left: var(--space-24);">
                        ${recomendacoes.map(rec => `<li style="margin-bottom: var(--space-8);">${rec}</li>`).join('')}
                    </ul>
                </div>
                
                <div style="text-align: center; padding: var(--space-24); background: rgba(255, 51, 51, 0.1); border-radius: var(--radius-base); border: 1px solid var(--color-primary);">
                    <h4 style="color: var(--color-text); margin-bottom: var(--space-12);">🎯 Próximo Passo</h4>
                    <p style="color: var(--color-text-light); margin-bottom: var(--space-16);">Nossa equipe especializada analisará suas respostas em detalhes e entrará em contato em até 24 horas com um plano de ação personalizado para elevar sua maturidade logística.</p>
                    <p style="color: var(--color-text); font-weight: 600; margin: 0;">Obrigado por confiar na JGM4 Consultoria!</p>
                </div>
            `;
            
            resultadoDiv.classList.remove('hidden');
            resultadoDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Simular envio para servidor (em produção, fazer requisição AJAX real)
            setTimeout(() => {
                alert('✅ Diagnóstico gerado com sucesso!\n\nSeu Índice de Maturidade Logística foi calculado. Role para baixo para ver os detalhes e recomendações personalizadas.');
            }, 500);
        }

        // Smooth scroll for all anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll suave para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });