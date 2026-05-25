<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Lang file
 *
 * @package   koperedashboard_benchmark
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['back_to_benchmark'] = 'Voltar ao benchmark';
$string['cap_run'] = 'Executar benchmark';
$string['cap_run_desc'] = 'Pode executar testes sintéticos de benchmark no Kopere Dashboard.';
$string['cap_view'] = 'Ver benchmark';
$string['cap_view_desc'] = 'Pode aceder à área de benchmark e ver recomendações de desempenho.';
$string['check_backup_auto_active'] = 'Cópia de segurança automática';
$string['check_cachejs'] = 'Cache JavaScript';
$string['check_debug'] = 'Nível de depuração';
$string['check_debugdisplay'] = 'Mostrar mensagens de depuração';
$string['check_themedesignermode'] = 'Modo de desenhador de temas';
$string['debug_value'] = 'Ativado ({$a})';
$string['environment_db'] = 'Base de dados';
$string['environment_kopere_dashboard'] = 'Kopere Dashboard';
$string['environment_memory'] = 'memory_limit';
$string['environment_moodle'] = 'Moodle';
$string['environment_opcache'] = 'OPcache';
$string['environment_opcache_details'] = 'Detalhes do OPcache';
$string['environment_opcache_warning'] = 'Mantenha o OPcache ativado em produção. Guarda scripts PHP compilados em memória, reduz a utilização da CPU e melhora o tempo de resposta.';
$string['environment_os'] = 'Sistema operativo';
$string['environment_os_windows_warning'] = 'O Windows não é recomendado para ambientes Moodle em produção. Prefira Linux para melhor compatibilidade, estabilidade e desempenho. <a href="https://docs.moodle.org/en/Complete_install_packages_for_Windows" target="_blank" rel="noopener noreferrer">Documentação do Moodle: o pacote completo de instalação para Windows não é recomendado para produção</a>.';
$string['environment_php'] = 'PHP';
$string['environment_title'] = 'Ambiente';
$string['environment_xsendfile'] = 'X-Sendfile';
$string['environment_xsendfile_warning'] = 'Mantenha o X-Sendfile ativado em produção. Permite que o servidor web entregue ficheiros diretamente, reduzindo o uso de memória do PHP e melhorando transferências de ficheiros grandes.';
$string['execute_title'] = 'Executar benchmark';
$string['help_recommendations'] = 'Estas recomendações ajudam a interpretar se o ambiente está configurado para produção. Não substituem uma análise detalhada da base de dados, Redis, cron, discos ou cache reversa.';
$string['iterations'] = 'Iterações';
$string['label_disabled'] = 'Desativado';
$string['label_enabled'] = 'Ativado';
$string['manage_intro'] = 'Execute um pequeno conjunto de testes sintéticos para obter uma visão rápida do desempenho geral do servidor Moodle. Os testes medem leituras simples da base de dados, ida e volta em disco, JSON, hash e processamento de strings.';
$string['manage_warning'] = 'Os resultados são comparativos. Idealmente, execute-os sempre no mesmo servidor e compare antes/depois de alterações ao PHP, base de dados, disco, cache, Redis, Nginx ou plugins.';
$string['menu_desc'] = 'Mede o tempo da base de dados, disco e CPU com recomendações rápidas de produção.';
$string['menu_title'] = 'Benchmark';
$string['opcache_details_value'] = 'CLI: {$a->enablecli}<br>memória: {$a->memory} MB<br>máx. ficheiros: {$a->maxfiles}<br>validar timestamps: {$a->timestamps}<br>freq. revalidação: {$a->revalidate}';
$string['peakmemory'] = 'Pico de memória';
$string['pluginname'] = 'Benchmark';
$string['recommend_backup_auto_active'] = 'Evite cópias de segurança automáticas durante horas de pico. Prefira janelas fora do horário de maior utilização.';
$string['recommend_cachejs'] = 'Em produção, mantenha a cache JavaScript ativada para reduzir processamento e transferência.';
$string['recommend_debug'] = 'A depuração ativa aumenta o custo de processamento e o ruído. Mantenha-a desativada em produção.';
$string['recommend_debugdisplay'] = 'A apresentação de mensagens de depuração diretamente no ecrã deve estar desativada em produção.';
$string['recommend_themedesignermode'] = 'O modo de desenhador de temas deve estar desativado em produção para evitar recompilação de CSS e quebras de desempenho.';
$string['recommendation'] = 'Recomendação';
$string['recommendations_title'] = 'Verificações rápidas de configuração';
$string['result_status'] = 'Estado';
$string['results_title'] = 'Resultados dos testes';
$string['run_benchmark'] = 'Executar benchmark';
$string['status_attention'] = 'Atenção';
$string['status_fast'] = 'Rápido';
$string['status_slow'] = 'Lento';
$string['summary_title'] = 'Resumo';
$string['test_db_desc'] = 'Leituras repetidas de pequenos registos de configuração da base de dados.';
$string['test_db_name'] = 'Base de dados';
$string['test_files_desc'] = 'Escrita, leitura e remoção de um ficheiro temporário local.';
$string['test_files_name'] = 'Sistema de ficheiros';
$string['test_hash_desc'] = 'Rondas repetidas de SHA-256 para medir o desempenho bruto da CPU.';
$string['test_hash_name'] = 'Hash / CPU';
$string['test_json_desc'] = 'Codificação e descodificação de estruturas JSON de tamanho médio.';
$string['test_json_name'] = 'JSON';
$string['test_name'] = 'Teste';
$string['test_string_desc'] = 'Limpeza e análise simples de conteúdo semelhante a HTML.';
$string['test_string_name'] = 'Strings / HTML';
$string['time_elapsed'] = 'Tempo';
$string['total_time'] = 'Tempo total';
$string['value'] = 'Valor';
$string['xsendfile_value'] = 'Ativado ({$a->header}<br>aliases: {$a->aliases})';
