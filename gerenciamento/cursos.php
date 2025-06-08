<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit();
}

// Conexão MySQLi
$conn = new mysqli("localhost", "root", "", "code_masters");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$aluno_id = $_SESSION['id'];

// Consulta aos cursos
$query = "SELECT * FROM curso";
$result = $conn->query($query);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos Disponíveis - CodeMasters</title>
    <style>
        :root {
            --primary: #4c1d95;       /* Roxo escuro */
            --secondary: #8b5cf6;     /* Roxo claro */
            --accent: #f43f5e;        /* Coral/rosa */
            --light: #f3e8ff;         /* Lilás claro */
            --dark: #2e1065;          /* Roxo profundo */
            --text: #1e1b4b;          /* Roxo neutro */
            --text-light: #c4b5fd;    /* Lilás suave */
            --white: #ffffff;         /* Branco */
            --roxo_menu: #1f0660;
            --esverdeado: #39ff14;
            --background: #170448;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px 0;
        }
        
        .header h1 {
            color: var(--secondary);
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        .header h1::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background-color: var(--secondary);
            bottom: -10px;
            left: 25%;
            border-radius: 2px;
        }
        
        .courses-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;

        }
        
        .course-card {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 25px;
        }
        
        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .course-title {
            color: var(--secondary);
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .course-description {
            color: var(--text);
            margin-bottom: 20px;
            min-height: 60px;
        }
        
        .course-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            border: none;
            font-size: 1em;
        }
        
        .btn-primary {
            background-color: var(--secondary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: #7c3aed;
        }
        
        .btn-secondary {
            background-color: transparent;
            color: var(--secondary);
            border: 2px solid var(--secondary);
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary);
            color: var(--white);
        }
        
        .already-enrolled {
            color: var(--accent);
            font-weight: 600;
            text-align: center;
            padding: 12px;
        }

        .botoes-baixo{
            text-align: center;
            margin-bottom: 40px;
            padding: 50px 0;
        }
        
        @media (max-width: 768px) {
            .courses-container {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .course-actions {
                flex-direction: column;
                gap: 10px;
            
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CURSOS DISPONÍVEIS</h1>
    </div>
    
    <div class="courses-container">
        <?php 
        while ($curso = $result->fetch_assoc()):
            // Verifica se o aluno já está inscrito
            $check_query = "SELECT * FROM inscricao WHERE id_aluno = ? AND id_curso = ?";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("ii", $aluno_id, $curso['id_curso']);
            $stmt->execute();
            $inscrito = $stmt->get_result()->num_rows > 0;
            $stmt->close();
        ?>
        <div class="course-card">
            <h3 class="course-title"><?= htmlspecialchars($curso['titulo']) ?></h3>
            <p class="course-description"><?= nl2br(htmlspecialchars($curso['descricao'])) ?></p>
            
            <div class="course-actions">
                <a href="curso.php?id_curso=<?= $curso['id_curso'] ?>" class="btn btn-primary">Começar Curso</a>
                
                <?php if ($inscrito): ?>
                    <p class="already-enrolled">Já inscrito</p>
                <?php else: ?>
                    <form method="POST" action="inscrever.php" style="flex: 1;">
                        <input type="hidden" name="curso_id" value="<?= $curso['id_curso'] ?>">
                        <button type="submit" class="btn btn-secondary">Inscrever-se</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="botoes-baixo">
        <div class="navigation-buttons">
            <a href="../index.php" class="btn btn-secondary">  Página Inicial </a>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>