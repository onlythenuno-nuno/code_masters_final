<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['id'])) {
    echo "Você precisa estar logado para acessar esta página.";
    exit();
}

if (!isset($_GET['id_curso'])) {
    echo "Curso não especificado.";
    exit();
}

$id_curso = $_GET['id_curso'];
$id_aluno = $_SESSION['id'];

// Verifica se aula já foi assistida
function aulaJaAssistida($conn, $id_aluno, $id_aula) {
    $query = "SELECT * FROM aulas_assistidas WHERE id_aluno = '$id_aluno' AND id_aula = '$id_aula'";
    $result = mysqli_query($conn, $query);
    return (mysqli_num_rows($result) > 0);
}

$curso_query = mysqli_query($conn, "SELECT * FROM curso WHERE id_curso = '$id_curso'");
$curso = mysqli_fetch_assoc($curso_query);

if (!$curso) {
    echo "Curso não encontrado.";
    exit();
}

$aulas_query = mysqli_query($conn, "SELECT * FROM aula WHERE id_curso = '$id_curso'");
function extrairUrlEmbed($iframe) {
    if (preg_match('/src="([^"]+)"/', $iframe, $matches)) {
        return $matches[1];
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Aulas de <?php echo htmlspecialchars($curso['titulo']); ?></title>
  <style>
    :root {
      --primary-color: #39ff14;
      --primary-hover: #2bc410;
      --dark-bg: #111827;
      --card-bg: #1f2937;
      --text-light: #f9fafb;
      --text-muted: #d1d5db;
      --border-radius: 10px;
      --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }

    body {
      background-color: #170448;
      color: var(--text-light);
      padding: 20px;
      line-height: 1.6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    h1 {
      color: var(--primary-color);
      margin-bottom: 15px;
      font-size: 1.8rem;
    }

    .curso-descricao {
      margin-bottom: 25px;
      color: var(--text-muted);
      font-size: 1.1rem;
    }

    .aula-container {
      background-color: #1f0660;
      padding: 20px;
      border-radius: var(--border-radius);
      margin-bottom: 30px;
      box-shadow: var(--box-shadow);
    }

    .aula-container h3 {
      margin-bottom: 15px;
      color: var(--text-light);
      font-size: 1.3rem;
    }

    .video-container {
      position: relative;
      padding-bottom: 56.25%;
      height: 0;
      overflow: hidden;
      border-radius: var(--border-radius);
      margin-bottom: 15px;
    }

    .video-container iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: none;
    }

    .controls {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin: 15px 0;
    }

    .btn-concluido {
      transition: all 0.3s ease;
      font-size: 1rem;
      font-weight: 600;
      background-color: #10b981;
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }

    .btn-concluido:hover {
      background-color: #059669;
    }

    .btn-concluido:disabled {
      background-color: #6b7280;
      cursor: not-allowed;
    }

    .btn-concluido.salvando {
      background-color: #f59e0b;
    }

    .progress {
      height: 8px;
      background: #374151;
      border-radius: 4px;
      margin: 15px 0;
      overflow: hidden;
    }

    .progress-bar {
      height: 100%;
      background: var(--primary-color);
      border-radius: 4px;
      width: 0%;
      transition: width 0.5s ease;
    }

    .anotacoes {
      margin-top: 20px;
    }

    .anotacoes h4 {
      margin-bottom: 8px;
      font-size: 1.1rem;
      color: white;
    }

    textarea {
      width: 100%;
      min-height: 100px;
      background-color: whitesmoke;
      border: 1px solid #334155;
      border-radius: var(--border-radius);
      padding: 10px;
      font-size: 1rem;
      color: #333;
      resize: vertical;
    }

    .btn-salvar {
      margin-top: 10px;
      background-color: #2563eb;
      color: white;
      border: none;
      padding: 8px 14px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-salvar:hover {
      background-color: #1d4ed8;
    }

    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }
      
      h1 {
        font-size: 1.5rem;
      }
      
      .aula-container {
        padding: 15px;
      }
    }
  </style>
</head>
<body>
  <h1><?php echo htmlspecialchars($curso['titulo']); ?></h1>
  <p class="curso-descricao"><?php echo htmlspecialchars($curso['descricao']); ?></p>

  <?php
  if (mysqli_num_rows($aulas_query) > 0) {
      while ($aula = mysqli_fetch_assoc($aulas_query)) {
          $aula_id = $aula['id_aula'];
          $ja_assistida = aulaJaAssistida($conn, $id_aluno, $aula_id);
          
          echo "<div class='aula-container'>";
          
          $video_url = (strpos($aula['link_video'], '<iframe') !== false) 
              ? extrairUrlEmbed($aula['link_video']) 
              : $aula['link_video'];
          
          echo "<div class='video-container'>";
          echo "<iframe id='video$aula_id' src='" . htmlspecialchars($video_url) . "' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
          echo "</div>";

          echo "<div class='controls'>";
          echo "<button class='btn-speed' onclick='setSpeed($aula_id, 0.5)'>0.5x</button>";
          echo "<button class='btn-speed' onclick='setSpeed($aula_id, 1)'>1x</button>";
          echo "<button class='btn-speed' onclick='setSpeed($aula_id, 1.5)'>1.5x</button>";
          echo "<button class='btn-speed' onclick='setSpeed($aula_id, 2)'>2x</button>";
          echo "</div>";

          if (!empty($aula['link_pdf'])) {
              echo "<br><a class='pdf-link' href='" . htmlspecialchars($aula['link_pdf']) . "' target='_blank'>Baixar Material PDF</a>";
          }

          echo "<div class='progress'>";
          echo "<div id='progress-$aula_id' class='progress-bar' style='width:" . ($ja_assistida ? '100%' : '0%') . "'></div>";
          echo "</div>";

          // Botão de concluído - sempre visível
          echo "<button class='btn-concluido' id='btnConcluido$aula_id' onclick='marcarConcluido($aula_id)'";
          echo $ja_assistida ? " disabled>Aula Concluída!" : ">Marcar como Concluída";
          echo "</button>";

          echo "<div class='anotacoes'>";
          echo "<h4>Anotações da Aula</h4>";
          echo "<textarea id='anotacao$aula_id' placeholder='Escreva suas anotações aqui...'></textarea>";
          echo "<button class='btn-salvar' onclick='salvarAnotacao($aula_id)'>Salvar Anotação</button>";
          echo "</div>";

          echo "</div>"; 
      }
  } else {
      echo "<p>Não há aulas disponíveis para este curso ainda.</p>";
  }
  ?>

  <script>
    // Função para marcar como concluído
    function marcarConcluido(aulaId) {
      const btn = document.getElementById('btnConcluido' + aulaId);
      btn.disabled = true;
      btn.classList.add('salvando');
      btn.innerHTML = 'Salvando...';
      
      fetch('marcar_assistida.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_aula=${aulaId}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          btn.innerHTML = 'Aula Concluída!';
          btn.classList.remove('salvando');
          document.getElementById('progress-' + aulaId).style.width = '100%';
        } else {
          btn.innerHTML = 'Erro ao salvar';
          btn.classList.remove('salvando');
          btn.disabled = false;
        }
      })
      .catch(error => {
        btn.innerHTML = 'Erro de conexão';
        btn.classList.remove('salvando');
        btn.disabled = false;
      });
    }

    // Função para controlar velocidade do vídeo
    function setSpeed(aulaId, speed) {
      const iframe = document.getElementById('video' + aulaId);
      if (iframe) {
        iframe.contentWindow.postMessage(JSON.stringify({
          event: 'command',
          func: 'setPlaybackRate',
          args: [speed]
        }), '*');
      }
    }

    // Função para salvar anotações
    function salvarAnotacao(id) {
      const texto = document.getElementById('anotacao' + id).value;
      localStorage.setItem('anotacao_' + id, texto);
      alert('Anotações salvas!');
    }

    // Carrega anotações salvas
    function carregarAnotacoes() {
      document.querySelectorAll('textarea').forEach(textarea => {
        const id = textarea.id.replace('anotacao', '');
        const texto = localStorage.getItem('anotacao_' + id);
        if (texto) textarea.value = texto;
      });
    }

    // Carrega anotações quando a página é aberta
    window.addEventListener('load', carregarAnotacoes);
  </script>
</body>
</html>