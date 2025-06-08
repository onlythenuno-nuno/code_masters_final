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

$query_eventos = "SELECT e.*, 
    IF(NOW() > e.data_evento, 'concluido',
        IF(EXISTS (SELECT 1 FROM eventos_participantes ep WHERE ep.id_aluno = $aluno_id AND ep.id_eventos = e.id_eventos), 'pendente', 'proximo')) AS status_evento,
    (SELECT 1 FROM eventos_participantes ep WHERE ep.id_aluno = $aluno_id AND ep.id_eventos = e.id_eventos LIMIT 1) AS inscrito
FROM eventos e
ORDER BY e.data_evento ASC";

$result = mysqli_query($conn, $query_eventos);

// Eventos que o aluno participa
$query_participando = "SELECT e.* FROM eventos e
JOIN eventos_participantes ep ON ep.id_eventos = e.id_eventos
WHERE ep.id_aluno = $aluno_id
ORDER BY e.data_evento ASC";
$result_participando = mysqli_query($conn, $query_participando);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendário de Eventos</title>
  <style>
    :root {
      --primary: #170448;
      --secondary: #8b5cf6;
      --accent: #f43f5e;
      --light: #f3e8ff;
      --dark: #2e1065;
      --text: #1e1b4b;
      --white: #fff;
      --roxo_menu: #1f0660;
      --text-light: #c4b5fd;
      --esverdeado: #2fc10e;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--primary);
      color: var(--text);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 30px 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;

    }

    @media (max-width: 608px) {
      .header{
        flex-direction: column;
        
      }

      .header > *{
        padding-bottom: 30px;
      }
    }

    .page-title {
      color: var(--white);
      font-size: 2em;
      margin: 0;
    }

    .back-btn {
      padding: 10px 20px;
      background-color: var(--secondary);
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .back-btn:hover {
      background-color: var(--dark);
      transform: translateY(-2px);
    }

    .section {
      background-color: white;
      border-radius: 8px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .section-title {
      color: var(--primary);
      margin-top: 0;
      margin-bottom: 20px;
      font-size: 1.5em;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .evento {
      background-color: white;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      border-left: 4px solid var(--secondary);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .evento:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .evento-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .evento-date {
      font-size: 1.1em;
      font-weight: 600;
      color: var(--primary);
    }

    .evento-title {
      font-size: 1.2em;
      font-weight: 500;
      margin-bottom: 15px;
      color: var(--text);
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85em;
      font-weight: 600;
    }

    .status-concluido {
      background-color: #d4edda;
      color: #155724;
    }

    .status-pendente {
      background-color: #fff3cd;
      color: #856404;
    }

    .status-proximo {
      background-color: #cce5ff;
      color: #004085;
    }

    .participar-btn {
      background-color: var(--esverdeado);
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 500;
      transition: background-color 0.3s;
    }

    .participar-btn:hover {
      background-color: #26980c;
    }

    .notificacao {
      background-color: #fff3cd;
      color: #856404;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 25px;
      border-left: 4px solid #ffc107;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .notificacao::before {
      content: "📢";
      font-size: 1.2em;
    }

    .empty-state {
      color: var(--text-light);
      font-style: italic;
      text-align: center;
      padding: 20px;
    }
    
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1 class="page-title">Calendário de Eventos</h1>
      <div class="voltar">
        <a href="../index.php" class="back-btn">Voltar</a>
        <a href="dashboard.php" class="back-btn">Dashboard</a>
      </div>
    </div>

    <div class="section">
      <h2 class="section-title">Próximos Eventos</h2>

      <?php
      $notificacao = mysqli_query($conn, "SELECT * FROM eventos e 
        LEFT JOIN eventos_participantes ep ON ep.id_eventos = e.id_eventos AND ep.id_aluno = $aluno_id
        WHERE e.data_evento BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 DAY)
        AND ep.id_eventos IS NULL");

      if (mysqli_num_rows($notificacao) > 0): ?>
        <div class="notificacao">
          Você tem eventos nos próximos 3 dias! Verifique abaixo e participe.
        </div>
      <?php endif; ?>

      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($evento = mysqli_fetch_assoc($result)): ?>
          <div class="evento">
            <div class="evento-header">
              <span class="evento-date"><?= date("d/m/Y", strtotime($evento['data_evento'])) ?></span>
              <span class="status-badge status-<?= $evento['status_evento'] ?>">
                <?= strtoupper($evento['status_evento']) ?>
              </span>
            </div>
            
            <h3 class="evento-title"><?= htmlspecialchars($evento['titulo']) ?></h3>
            
            <?php if (!$evento['inscrito'] && $evento['status_evento'] != 'concluido'): ?>
              <form method="POST" action="participar_evento.php">
                <input type="hidden" name="id_evento" value="<?= $evento['id_eventos'] ?>">
                <button type="submit" class="participar-btn">Participar</button>
              </form>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="empty-state">Nenhum evento disponível no momento.</p>
      <?php endif; ?>
    </div>

    <div class="section">
      <h2 class="section-title">Eventos Confirmados</h2>
      
      <?php if (mysqli_num_rows($result_participando) > 0): ?>
        <?php while($part = mysqli_fetch_assoc($result_participando)): ?>
          <div class="evento">
            <div class="evento-header">
              <span class="evento-date"><?= date("d/m/Y", strtotime($part['data_evento'])) ?></span>
              <span class="status-badge <?= (strtotime($part['data_evento']) < time() ? 'status-concluido' : 'status-pendente') ?>">
                <?= (strtotime($part['data_evento']) < time()) ? 'CONCLUÍDO' : 'CONFIRMADO' ?>
              </span>
            </div>
            <h3 class="evento-title"><?= htmlspecialchars($part['titulo']) ?></h3>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="empty-state">Você não está participando de nenhum evento.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>