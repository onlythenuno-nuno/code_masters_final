<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexao.php';

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_nivel'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="favicon/IMG_2517.PNG" type="image/x-icon">

    <title>Painel do Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['mensagem'])): ?>
            <div class="mensagem"><?= htmlspecialchars($_GET['mensagem']) ?></div>
        <?php endif; ?>
        
        <h1 style="color: white;">Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?> <small>(Admin Comum)</small></h1>
        
    
        <div class="nav-buttons">
            <button onclick="showSection('cursos')" class="btn"><i class="fas fa-book"></i> Cursos</button>
            <button onclick="showSection('aulas')" class="btn"><i class="fas fa-video"></i> Aulas</button>
            <button onclick="showSection('alunos')" class="btn"><i class="fas fa-users"></i> Alunos</button>
        </div>

        
        <div id="cursos-section" class="card">
            <h2><i class="fas fa-book"></i> Gerenciar Cursos</h2>
            
            <h3>Adicionar Novo Curso</h3>
            <form method="POST" action="salvar_curso.php">
                <input type="text" name="titulo" placeholder="Título do Curso" required>
                <textarea name="descricao" placeholder="Descrição do curso" required></textarea>
                <button type="submit" class="btn"><i class="fas fa-save"></i> Salvar Curso</button>
            </form>
            
            <h3>Meus Cursos Cadastrados</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $admin_id = $_SESSION['admin_id'];
                        $cursos = mysqli_query($conn, "SELECT * FROM curso WHERE admin_id = $admin_id ORDER BY titulo");
                        
                        if (mysqli_num_rows($cursos) > 0) {
                            while ($curso = mysqli_fetch_assoc($cursos)) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($curso['titulo']) . "</td>
                                    <td>" . nl2br(htmlspecialchars($curso['descricao'])) . "</td>
                                    <td>
                                        <a href='editar_curso.php?id={$curso['id']}' class='btn btn-secondary'><i class='fas fa-edit'></i> Editar</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Nenhum curso cadastrado por você.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Seção de Aulas -->
        <div id="aulas-section" class="card" style="display:none;">
            <h2><i class="fas fa-video"></i> Gerenciar Aulas</h2>
            
            <h3>Adicionar Nova Aula</h3>
            <form method="POST" action="salvar_aula.php">
                <select name="id_curso" required>
                    <option value="">Selecione o Curso</option>
                    <?php
                    $cursos = mysqli_query($conn, "SELECT id, titulo FROM curso ORDER BY titulo");
                    while ($curso = mysqli_fetch_assoc($cursos)) {
                        echo "<option value='{$curso['id']}'>{$curso['titulo']}</option>";
                    }
                    ?>
                </select>
                <input type="text" name="titulo" placeholder="Título da Aula" required>
                <input type="text" name="link_video" placeholder="Link do Vídeo (YouTube, Vimeo)" required>
                <input type="text" name="link_pdf" placeholder="Link do PDF (opcional)">
                <input type="number" name="ordem" placeholder="Ordem da aula" min="1" required>
                <button type="submit" class="btn"><i class="fas fa-save"></i> Salvar Aula</button>
            </form>
            
            <h3>Aulas Cadastradas</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Título</th>
                            <th>Ordem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $aulas = mysqli_query($conn, "
                            SELECT a.*, c.titulo as curso_titulo 
                            FROM aula a
                            JOIN curso c ON a.id_curso = c.id
                            ORDER BY c.titulo, a.ordem
                        ");
                        
                        if (mysqli_num_rows($aulas) > 0) {
                            while ($aula = mysqli_fetch_assoc($aulas)) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($aula['curso_titulo']) . "</td>
                                    
                                    <td>" . htmlspecialchars($aula['ordem']) . "</td>
                                    <td>
                                        <a href='editar_aula.php?id={$aula['id']}' class='btn btn-secondary'><i class='fas fa-edit'></i> Editar</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Nenhuma aula cadastrada.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

       
        <div id="alunos-section" class="card" style="display:none;">
            <h2><i class="fas fa-users"></i> Alunos Inscritos</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Aluno</th>
                            <th>Email</th>
                            <th>Data Inscrição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $inscricoes = mysqli_query($conn, "
                            SELECT c.titulo as curso, a.nome_aluno, a.email_aluno, i.data_inscricao
                            FROM inscricao i
                            JOIN curso c ON i.id_curso = c.id
                            JOIN aluno a ON i.id_aluno = a.id_aluno
                            ORDER BY i.data_inscricao DESC
                        ");
                        
                        if (mysqli_num_rows($inscricoes) > 0) {
                            while ($inscricao = mysqli_fetch_assoc($inscricoes)) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($inscricao['curso']) . "</td>
                                    <td>" . htmlspecialchars($inscricao['nome_aluno']) . "</td>
                                    <td>" . htmlspecialchars($inscricao['email_aluno']) . "</td>
                                    <td>" . date('d/m/Y', strtotime($inscricao['data_inscricao'])) . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Nenhum aluno inscrito.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showSection(section) {
            
            document.querySelectorAll('.card').forEach(card => {
                card.style.display = 'none';
            });
            
            document.getElementById(section + '-section').style.display = 'block';
        }
        
        
        showSection('cursos');
    </script>
</body>
<style>
    :root {
        --primary: #00cc66; /* Verde suavizado */
        --dark: #12053d;
        --darker: #0a0324;
        --light: #ffffff;
        --gray: #1e1e3a;
    }

    body {
        font-family: 'Montserrat', sans-serif;
        background-color: var(--darker);
        color: var(--light);
        margin: 0;
        padding: 20px;
        color: var(--dark);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .card {
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 25px;
        margin-bottom: 30px;
        background-color: var(--dark);
    }

    .nav-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn {
        background-color: var(--primary);
        color: black;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: bold;
    }

    .btn:hover {
        background-color: #00994d; /* tom mais escuro do verde suavizado */
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: var(--secondary);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: var(--dark);
        box-shadow: 0 0 10px rgba(0, 204, 102, 0.2);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 40px;
        min-width: 600px;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: var(--primary);
        color: white;
    }

    tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .mensagem {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        background-color: #dff0d8;
        color: #3c763d;
    }

    input, textarea, select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid var(--primary);
        border-radius: 5px;
        background-color: var(--gray);
        color: var(--light);
        font-size: 16px;
        transition: all 0.3s;
    }

    textarea {
        min-height: 100px;
        resize: vertical;
        outline: none;
        box-shadow: 0 0 8px var(--primary);
    }
</style>

</html>

