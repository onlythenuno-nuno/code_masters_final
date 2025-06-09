<?php
session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <link rel="shortcut icon" href="favicon/IMG_2517.PNG" type="image/x-icon" style="max-width: 120px;">
  <title>Code Masters</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }

    /* REMOVIDO O PADDING TOP DO BODY */
    body {
      background-color: #170448;
      color: white;
    }

    /* Barra de navegação - REMOVIDO POSITION FIXED */
    header {
      background-color: #1f0660;
      width: 100%;
      padding: 4px 0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    /* Container do menu */
    .nav-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0px 20px;
      max-width: 1400px;
      margin: 0 auto;
    }

    /* Logo */
    .logo-img {
      width: 130px;
    }

    /* Lista de itens do menu */
    .nav-list {
      display: flex;
      list-style: none;
    }

    .nav-list li {
      margin-left: 25px;
    }

    .nav-list a {
      color: white;
      text-decoration: none;
      font-size: 1.1rem;
      transition: color 0.3s;
      font-weight: 500;
    }

    .nav-list a:hover {
      color: #39ff14;
    }

    /* Botão do menu mobile */
    .menu-toggle {
      display: none;
      background: none;
      border: none;
      color: white;
      font-size: 1.8rem;
      cursor: pointer;
      padding: 5px 10px;
      order: 2;
      margin-left: auto;
    }

    /* Media Query para dispositivos móveis */
    @media (max-width: 768px) {
      .nav {
        width: 100%;
      }
      
      .nav-list {
        position: absolute;
        top: 80px;
        left: -100%;
        width: 100%;
        background: linear-gradient(180deg, #1f0660 0%, #170448 100%);;
        flex-direction: column;
        align-items: center;
        padding: 30px 0;
        transition: left 0.3s ease;
        z-index: 1000;
      }

      .nav-container{
        justify-content: space-between;
      }

      .nav-list.active {
        left: 0;
      }

      .nav-list li {
        margin: 20px 0;
      }

      .menu-toggle {
        display: block;
      }
      
      .logo {
        margin-right: auto;
      }
    }

    *{
    margin: 0;
  font-family: "Montserrat", sans-serif;

}
  



.hero .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; 
    padding: 2rem 2%;
    
}
.container{
    max-width: 180px;
}
.text-box {
    flex: 1;
    min-width: 300px;
}

.imag-l img {
    max-width: 100%; 
    height: auto;
}

.container > h2{
  padding-bottom: 10px;
}

p{
  padding: 20px 0;
  font-size: 1.2rem;
  text-align: start;
}

@media (max-width: 1000px) {
    .hero .container {
        flex-direction: column;

    }
    .imag-l {
        margin: 2rem 0 0 0 !important; 
    }
}

.container {
  display: grid;
  grid-template-columns: repeat(4, 1fr); 
  gap: 1.5rem;
  padding: 1rem;
  max-width: 1400px;
  margin: 0 auto;
}

.item {
  background-color: #170448;
  border: 2px solid #39ff14;
  padding: 1rem;
  border-radius: 8px;
  text-align: center;
  transition: transform 0.3s;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  min-height: 180px;
}

.item img {
  width: 60px;
  height: 60px;
  object-fit: contain;
  margin-bottom: 0.5rem;
}

.item h3 {
  font-size: 1.1rem;
  margin: 0.5rem 0;
  color: white;
}

.curso-info {
  font-size: 0.8rem;
  color: #ccc;
  margin-top: 0.5rem;
  display: none; 
}


.item:hover .curso-info {
  display: block;
}


@media (max-width: 900px) {
  .container {
    grid-template-columns: repeat(2, 1fr);
    max-width: 600px;
  }
}


@media (max-width: 600px) {
  .container {
    grid-template-columns: 1fr;
    max-width: 300px;
  }
  .curso-info {
    display: block !important;
  }
}
.imagens-c {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    padding: 2rem 5%;
}

.image-card {
    background-color: #1f0660;
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
    text-align: center;
}

.image-card img {
    max-width: 100px;
    height: auto;
    margin-bottom: 1rem;
}
.footer-container {
  background-color: #1f0660;
    color: white;
    padding: 2rem 5%;
    text-decoration: none
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.footer-section h3 {
    color: #fff;
    margin-bottom: 1rem;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.icones-f2 {
    max-width: 150px;
    height: auto;
}
.icones-f{
max-width: 30px;
height:auto ;
}

.container {
  background-color: #170448;

    display: grid;
    grid-template-columns: repeat(4, 1fr); 
    gap: 1.5rem;
    padding: 2rem 5%;
    max-width: 1200px; 
    margin: 0 auto; 
}

.item {
  background-color: #170448;
  border: 2px solid #39ff14;
    
    padding: 0rem;
    border-radius: 8px;
    text-align: center;
    transition: transform 0.3s;
    aspect-ratio: 1/1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.item img {
    max-width: 70px; 
    height: auto;
    margin-bottom: 1rem;
}

.item:hover {
    transform: scale(1.05);
}

.curso-info {
    display: none;
    margin-top: 0.5rem;
}


@media (max-width: 1024px) {
    .container {
        grid-template-columns: repeat(2, 1fr);
    }
}


@media (max-width: 600px) {
    .container {
        grid-template-columns: 1fr;
    }
}


@media (max-width: 768px) {
    .curso-info {
        display: block !important;
    }
}
button{
    background-color: #170448;
  color: white;
  padding: 15px 30px;
  text-decoration: none;
  font-size: 18px;
  border: 2px solid #39ff14;
  cursor: pointer;
  border-radius: 15px;
  transition: box-shadow 0.3s ease-in-out;
  margin-left: 25px;
}
.hero .button {
    background-color: #170448;
    color: white;
    padding: 15px 30px;
    text-decoration: none;
    font-size: 18px;
    border: 2px solid #39ff14;
    cursor: pointer;
    border-radius: 15px;
    transition: box-shadow 0.3s ease-in-out;
    margin-left: 25px;
  }
  
  
  .hero .button:hover {
    background-color: none;
    box-shadow: 0 0 10px #3ff755, 0 0 20px #3ff755, 0 0 30px #3ff755;
  }


  /* Modal Styles - Estilo igual ao print */
.modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
}

.modal-content {
  background-color: #170448;
  margin: 15% auto;
  padding: 20px;
  border: 2px solid #39ff14;
  border-radius: 10px;
  width: 80%;
  max-width: 400px;
  text-align: center;
  color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.modal-title {
  font-size: 1.5rem;
  margin-bottom: 15px;
  color: #39ff14;
}

.modal-message {
  margin-bottom: 10px;
  line-height: 1.5;
}

.modal-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.modal-button {
  padding: 3px 25px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s;
  border: 2px solid transparent;
}

.modal-button.cancel {
  background-color: transparent;
  color: white;
  border: 2px solid #ff3333;
}

.modal-button.cancel:hover {
  background-color: #ff3333;
  color: white;
}

.modal-button.confirm {
  background-color: #39ff14;
  color: #170448;
  border: 2px solid #39ff14;
}

.modal-button.confirm:hover {
  background-color: #170448;
  color: #39ff14;
  box-shadow: 0 0 10px #39ff14;
}



  </style>
</head>

<body>
  <header>
    <div class="nav-container">
      <div class="logo">
        <a href="index.php"><img src="logo.png" alt="logo" class="logo-img"></a>
      </div>
    
      <button class="menu-toggle" id="mobile-menu">
        ☰
      </button>
    
      <nav class="nav">
        <ul class="nav-list" id="navLinks">
          <li>
            <a href="#" onclick="verificarCursos()">Cursos</a>
          </li>
          <li>
            <a href="#" onclick="verificarCertificados()">Certificados</a>
          </li>
          <li>
            <a href="#" onclick="verificarEventos()">Eventos</a>
          </li>
          <li><a href="metodo.php">Nosso Método</a></li>
          <li><a href="sobre_us.php">Sobre</a></li>
          <li>
            <a href="#" onclick="verificarSuporte()">Suporte</a>
          </li>
          <?php if (isset($_SESSION['nome_aluno'])): ?>
            <li><a href="aluno/dashboard.php" style="color: #39ff14;">Meu Perfil</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <section class="hero">
    <div class="container">
      <div class="text-box">
        <h2 style="color: white;">Você deseja aprender programação? <br>Temos um convite <br> pra você!</h2>
        <p style="color: white;">Transforme sua paixão por tecnologia em uma carreira de sucesso! No nosso site, oferecemos uma jornada
          completa, desde os fundamentos até as técnicas mais avançadas de programação com materiais de alta qualidade,
          incluindo PDFs detalhados e vídeos tutoriais, você terá todas as ferramentas necessárias para se destacar no
          mundo da programação.</p>
          <br>
          <br>
          
        <a href="aluno/login.php" class="button" >SEJA UM DESENVOLVEDOR</a>
      </div>
      <br>
      <div class="imag-l" style="margin-left: -40px;">
        <img src="index imagem/IMG_1826.PNG" alt="" style="width: 600px;" height="auto">

      </div>
    </div>
  </section>
  
  <h1 style="text-align: center; color: white;
position: relative; border-top: 30px;">DISPONIVEIS NO NOSSO SITE</h1>


  <div class="container">
    <?php
    // Conexão com o banco de dados
    include 'config/db.php';

    // Consulta para buscar os cursos
    $sql = "SELECT id_curso, titulo, descricao FROM curso";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="item" style="color: white;">';
            // Usando a primeira letra do título como "ícone"
            echo '<div style="font-size: 2.8rem; color: #39ff14;  font-weight: bold; margin-bottom: 0.5rem;">' . substr($row['titulo'], 0, 1) . '</div>';
            echo '<h3>' . $row['titulo'] . '</h3>';
            echo '<div class="curso-info">' . $row['descricao'] . '</div>';
            echo '</div>';
        }
    } else {
        echo '<p style="color: white; grid-column: 1 / -1;">Nenhum curso disponível no momento.</p>';
    }
    $conn->close();
    ?>
  </div>

</div>
</body>
<br>
<h1 style="text-align: center;
        color: white;">PORQUE NOS ESCOLHER?</h1>
<div class="imagens-c">
  <div class="image-card">
    <img src="Ícones do centro/graduacao.png" alt="Imagem 1">
    <h3>Aprendizado Qualitativo</h3>
    <p>Obtenha Formacoes Solidas das principais linguagens de programacao</p>
  </div>
  <div class="image-card">
    <img src="Ícones do centro/socios.png" alt="Imagem 2">
    <h3>Colaboraçao Em Equipe</h3>
    <p>Trabalhe em projectos colaborativos e teste o seu desenvolvimento em grupo
    <p>
  </div>
  
  <div class="image-card">
    <img src="Ícones do centro/hacker 3.png" alt="Imagem 4">
    <h3>Segurança dos seus dados</h3>
    <p>Todos os seus dados serão protegidos, cuidamos com total Segurança os dados dos nossos usuarios</p>
  </div>
</div>
</body>

<div class="footer-container">
  <div class="footer-content">
    <div class="footer-section">
      <h3>Code Masters</h3>
      <ul>
        <li><a href="#" style="text-decoration: none; color: white;">Trabalhe Connosco</a></li>
        <li><a href="#" style="text-decoration: none; color: white;">Arquivo</a></li>
        <li><a href="#" style="text-decoration: none; color: white;">Portal dos alunos</a></li>
        <li><a href="http://localhost/C_masters_puro/admin/login.php" style="text-decoration: none; color: rgba(23, 4, 72, 0);">Portal dos alunos</a></li>
      </ul>
    </div>
    <div class="footer-section">
      <h3>Navegação</h3>
      <ul>
        <li><a href="sobre.html" style="text-decoration: none; color: white;">Sobre</a></li>
        <li><a href="metodo.html" style="text-decoration: none; color: white;">Nosso Método</a></li>
        <li><a href="suporte.html" style="text-decoration: none; color: white;">Suporte</a></li>



      </ul>
    </div>
    <div class="footer-section">
      <h3 style="text-decoration: none; color: white;"> Políticas e Termos</h3>
      <ul>
        <li><a href="#" style="text-decoration: none; color: white;">Política de Privacidade</a></li>
        <li><a href="#" style="color: white; text-decoration: none;">Termos de Uso</a></li>

      </ul>
    </div>
    <div class="footer-section">
      <h3>Contatos</h3>
      <ul>
        <li>Suporte: (244) 92560-7551</li>
        <li>Financeiro: (244) 95144-0702</li>
        <li>Comercial: (244) 93978-1010</li>
      </ul>
    </div>
    <div class="footer-section">
   
      <h3>Redes Sociais</h3>
      <ul class="social-links">
                  <li><a href="https://www.instagram.com/codemasters22/" target="_blank"><img src="login-icones/IMG_1802.PNG" alt="linkedin"
                        class="icones-f"></a></li>
                  <li><a href="https://youtube.com/@codemasters-p2x?si=GfeY0pREVbNrjd9d" target="_blank"><img src="login-icones/pngwing.com.png" alt="YouTube"
                        class="icones-f youtube">
                    </a></li><li><a href="https://www.facebook.com/profile.php?id=61577175148785" target="_blank"><img src="login-icones/IMG_1803.PNG" alt="twitter"
                        class="icones-f">
                    </a></li>
      </ul>
      <h3>Fale com a nossa equipe</h3>
      <ul>
        <li><a href="Codemasters.com.ao" style="color: white; text-decoration: none;">Codemasters.com.ao</a></li>
      </ul>
    </div>
  </div>
</div>

  <script>
    // JavaScript para o menu mobile
    const mobileMenuBtn = document.getElementById('mobile-menu');
    const navLinks = document.getElementById('navLinks');

    mobileMenuBtn.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });

    // Fechar o menu quando um link for clicado (opcional)
    document.querySelectorAll('.nav-list a').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('active');
      });
    });

    // Funções para o modal
function showModal(title, message) {
  const modalTitle = document.querySelector('.modal-title');
  const modalMessage = document.querySelector('.modal-message');
  
  modalTitle.textContent = title || 'Confirmar Acesso';
  modalMessage.textContent = message || 'Tem certeza que deseja acessar esta área? Você precisa estar logado.';
  document.getElementById('loginModal').style.display = 'block';
}

function closeModal() {
  document.getElementById('loginModal').style.display = 'none';
}

function redirectToLogin() {
  window.location.href = 'aluno/login.php';
}

// Funções de verificação atualizadas
function verificarSuporte() {
  <?php if (isset($_SESSION['nome_aluno'])): ?>
    window.location.href = 'suporte/suporte.php';
  <?php else: ?>
    showModal('Acesso ao Suporte', 'Para acessar o Suporte, você precisa fazer login.');
  <?php endif; ?>
}

function verificarCursos() {
  <?php if (isset($_SESSION['nome_aluno'])): ?>
    window.location.href = 'gerenciamento/cursos.php';
  <?php else: ?>
    showModal('Acesso aos Cursos', 'Para acessar os Cursos, você precisa fazer login.');
  <?php endif; ?>
}

function verificarCertificados() {
  <?php if (isset($_SESSION['nome_aluno'])): ?>
    window.location.href = 'certificado.php';
  <?php else: ?>
    showModal('Acesso aos Certificados', 'Para acessar os Certificados, você precisa fazer login.');
  <?php endif; ?>
}

function verificarEventos() {
  <?php if (isset($_SESSION['nome_aluno'])): ?>
    window.location.href = 'aluno/eventos.php';
  <?php else: ?>
    showModal('Acesso aos Eventos', 'Para acessar os Eventos, você precisa fazer login.');
  <?php endif; ?>
}

// Fechar o modal se clicar fora dele
window.addEventListener('click', function(event) {
  const modal = document.getElementById('loginModal');
  if (event.target === modal) {
    closeModal();
  }
});

    document.querySelectorAll('.item').forEach(item => {
      const info = item.querySelector('.curso-info');
      info.style.display = 'none'; 

      item.addEventListener('mouseenter', () => {
          info.style.display = 'block';
      });

      item.addEventListener('mouseleave', () => {
          info.style.display = 'none';
      });
    });

  </script>

<div id="loginModal" class="modal">
  <div class="modal-content">
    <h3 class="modal-title">Confirmar Acesso</h3>
    <p class="modal-message">Tem certeza que deseja acessar esta área? Você precisa estar logado.</p>
    <div class="modal-buttons">
      <button class="modal-button confirm" onclick="redirectToLogin()">Fazer Login</button>
      <button class="modal-button cancel" onclick="closeModal()">Cancelar</button>

    </div>
  </div>
</div>

</body>
</html>