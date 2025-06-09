<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit();
}

include 'conexao.php';
$aluno_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Aluno - CodeMasters</title>
    <style>
        :root {
            --primary: #170448;       /* Roxo escuro (sidebar) */
            --secondary: #8b5cf6;     /* Roxo claro (destaques/botões) */
            --accent: #f43f5e;        /* Coral/rosa (hover ou alerta) */
            --light: #f3e8ff;         /* Lilás claro (cards, fundos leves) */
            --dark: #2e1065;          /* Roxo profundo (sombreados) */
            --text: #1e1b4b;          /* Roxo neutro (texto principal) */
            --text-light: #c4b5fd;    /* Lilás suave (texto secundário) */
            --white: #ffffff;         /* Branco puro */
            --roxo_menu: #1f0660;
            --esverdeado: #39ff14;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light);
            color: var(--text);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar atualizada para corresponder ao painel_admin.php */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--dark) 100%);
            color: white;
            padding: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .logo-section {
            padding: 25px;
            background-color: rgba(0,0,0,0.1);
        }

        .logo {
            padding: 0px 10px;
            width: 230px;
        }

        .user-profile {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .user-profile h2 {
            margin: 15px 0 5px;
            font-size: 1.4em;
            color: white;
        }

        .user-email {
            font-size: 0.85em;
            color: var(--secondary);
        }

        .menu-section {
            padding: 15px 0;
        }

        .menu-section h3 {
            padding: 12px 25px;
            margin: 0;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--secondary);
            background-color: rgba(0,0,0,0.2);
        }

        .menu-item {
            padding: 14px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            color: white;
        }

        .menu-item:hover {
            background-color: rgba(255,255,255,0.05);
            border-left: 3px solid var(--secondary);
            padding-left: 30px;
            color: var(--secondary);
        }

        .menu-item.active {
            background-color: rgba(52, 152, 219, 0.1);
            border-left: 3px solid var(--secondary);
            color: var(--secondary);
        }

        .logout-section {
            padding: 20px;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .logout-item {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: var(--light);
            transition: color 0.3s;
        }

        .logout-item:hover {
            color: var(--accent);
        }

        /* Restante do CSS permanece igual */
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: var(--light);
            transition: margin-left 0.3s ease;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .courses-section {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            margin-top: 0;
            color: var(--text);
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .course-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .course-info {
            flex: 1;
        }

        .course-title {
            font-weight: bold;
            color: var(--text);
            margin-bottom: 5px;
            font-size: 1.4rem;
        }

        .course-description {
            color: var(--text-light);
            font-size: 1em;
        }

        .course-actions {
            display: flex;
            gap: 10px;
        }

        .course-btn {
            background-color: var(--secondary);
            color: var(--white);
            border: none;
            padding: 15px 22px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 1em;
            font-weight: bold;
        }

        .course-btn:hover {
            background-color: #7c3aed;
            transform: translateY(-2px);
        }

        .secondary-btn {
            background-color: var(--light);
            color: var(--text);
            border: 1px solid var(--secondary);
        }

        .secondary-btn:hover {
            background-color: #e9d5ff;
        }

        .calendar-section {
            background-color: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .events-title {
            font-weight: bold;
            margin: 15px 0 10px;
            color: var(--text);
        }

        .event-item {
            padding: 8px 0;
        }

        .event-date {
            font-weight: bold;
            color: var(--secondary);
        }

        #nomeDigitado::after {
            content: '|';
            animation: piscar 1s infinite;
            color: #170448;
            margin-left: 5px;
            }

            @keyframes piscar {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
            }

        /* Estilo melhorado para o hamburguer */
        .hamburger {
            display: none;
            font-size: 26px;
            background: var(--secondary);
            border: none;
            color: white;
            padding: 10px 15px;
            cursor: pointer;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 999;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .hamburger:hover {
            background: var(--dark);
            transform: scale(1.05);
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }

        /* NOVO: Responsividade */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }

            .hamburger {
                display: block;
            }

            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo-section">
                <a href="../index.php"><img src="../logo-grande/logo.png" class="logo" alt=""></a>
            </div>

            <div class="menu-section">
                <a href="mensagem_suporte.php" style="text-decoration: none;"><div class="menu-item">Suporte</div></a>
                <a href="eventos.php" style="text-decoration: none;"><div class="menu-item">Eventos</div></a>
                <a href="../quiz/index.html" style="text-decoration: none;"><div class="menu-item">Quiz</div></a>
                <a href="../index.php" style="text-decoration: none;"><div class="menu-item">Página Principal</div></a>
            </div>
            
            <div class="logout-section">
                <a href="logout.php" style="text-decoration: none;">
                    <div class="logout-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Terminar Sessão
                    </div>
                </a>
            </div>
        </div>

        <div class="overlay" id="overlay"></div>

        <div class="main-content">
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <div class="dashboard-header">
            <h1 class="section-title">Bem-vindo(a), <span id="nomeDigitado"></span></h1>
            </div>
            <div class="dashboard-header">
                <h1 class="section-title">Meus cursos</h1>
            </div>

            <div class="courses-section">
                <?php
                $cursos = mysqli_query($conn, "SELECT c.* FROM curso c 
                                            JOIN inscricao i ON c.id_curso = i.id_curso 
                                            WHERE i.id_aluno = '$aluno_id'");
                
                if (mysqli_num_rows($cursos) > 0) {
                    while ($curso = mysqli_fetch_assoc($cursos)) {
                        echo '<div class="course-card">';
                        echo '<div class="course-info">';
                        echo '<div class="course-title">'.$curso['titulo'].'</div>';
                        echo '<div class="course-description">'.$curso['descricao'].'</div>';
                        echo '</div>';
                        echo '<div class="course-actions">';
                        echo '<a href="gerenciamento/curso.php?id_curso='.$curso['id_curso'].'" class="course-btn secondary-btn">Continuar curso</a>';
                        echo '</div>';
                        echo '</div>';

                    }
                } else {
                    echo '<p style="color: var(--text);">Você não está inscrito em nenhum curso ainda.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        const nome = "<?php echo addslashes($_SESSION['nome_aluno']); ?>";
        const span = document.getElementById('nomeDigitado');
        let index = 0;

        function digitar() {
            if (index < nome.length) {
                span.innerHTML += `<span style="color:#2e1065;">${nome.charAt(index)}</span>`;
                index++;
                setTimeout(digitar, 100);
            }
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            // Impede a rolagem do corpo quando a sidebar está aberta
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        // Fechar a sidebar ao clicar no overlay
        document.getElementById('overlay').addEventListener('click', toggleSidebar);

        digitar();
    </script>
</body>
</html>