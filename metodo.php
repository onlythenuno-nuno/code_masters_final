<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/IMG_2517.PNG" type="image/x-icon">
    <title>Nossos Métodos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
      body {
  font-family: "Montserrat", sans-serif;
  margin: 0;
  padding: 0;
  background-color: #170448;
  color: white;
}

/* Barra de navegação - REMOVIDO POSITION FIXED */
header {
  background-color: #1f0660;
  width: 100%;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
  padding: 1px 0;
}

/* Container do menu */
.nav-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 20px;
  max-width: 1400px;
  margin: 0 auto;
  
}

/* Logo */
.logo-img {
  height: 30px;
  width: auto;
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
  font-size: 1.2rem;
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
@media (max-width: 1050px) {
  .nav {
    width: 100%;
  }

  .nav-list {
    position: absolute;
    top: 80px;
    left: -100%;
    width: 100%;
    background: linear-gradient(180deg, #1f0660 0%, #170448 100%);
    flex-direction: column;
    align-items: center;
    padding: 30px 0;
    transition: left 0.3s ease;
    z-index: 1000;
  }

  .nav-container {
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

.hero {
  background-color: #170448;
  color: white;
  padding: 30px 20px;
}

.container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}

.text-box {
  flex: 1 1 50%;
  min-width: 300px;
  padding: 20px;
}

.text-box h2 {
  font-size: 2rem;
  margin-bottom: 20px;
}

.text-box p {
  font-size: 1rem;
  margin-bottom: 30px;
  line-height: 1.6;
}

.imag-l {
  flex: 1 1 40%;
  min-width: 300px;
  text-align: center;
  margin: 20px 0;
}

.imag-l img {
  max-width: 100%;
  height: auto;
}

.button {
  display: inline-block;
  background-color: #170448;
  color: white;
  padding: 15px 30px;
  text-decoration: none;
  font-size: 18px;
  border: 2px solid #39ff14;
  cursor: pointer;
  border-radius: 10px;
  transition: box-shadow 0.3s ease-in-out;
}

.button:hover {
  box-shadow: 0 0 10px #3ff755, 0 0 20px #3ff755, 0 0 30px #3ff755;
}

h1 {
  color: white;
  text-align: center;
  font-size: 2rem;
  margin: 40px 0;
}

.methods-section {
  max-width: 1200px;
  margin: 50px auto;
  padding: 20px;
}

.grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  grid-gap: 20px;
}

.method-card {
  background-color: #003366;
  border: 2px solid #39ff14;
  color: white;
  text-align: center;
  padding: 30px 20px;
  border-radius: 10px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.method-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.method-icon {
  font-size: 3em;
  margin-bottom: 15px;
}

.method-card h2 {
  font-size: 1.5em;
  margin-bottom: 10px;
}

.method-card p {
  font-size: 1em;
  line-height: 1.5;
}

.footer-container {
  background-color: #003366;
  color: white;
  padding: 40px 20px;
  margin-top: 80px;
}

.footer-content {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  max-width: 1200px;
  margin: 0 auto;
}

.footer-section {
  flex: 1 1 200px;
  margin: 10px;
  min-width: 150px;
}

.footer-section h3 {
  font-size: 1.2em;
  margin-bottom: 15px;
}

.footer-section ul {
  list-style-type: none;
  padding: 0;
}

.footer-section ul li {
  margin-bottom: 8px;
}

.footer-section ul li a {
  color: white;
  text-decoration: none;
}

.footer-section ul li a:hover {
  text-decoration: underline;
}

.social-links {
  display: flex;
  gap: 10px;
  padding: 0;
}

.social-links li {
  list-style: none;
}

.icones-f {
  width: 30px;
  height: auto;
}

.icones-f2 {
  width: 120px;
  height: auto;
  margin-bottom: 10px;
}

/* Responsividade */
@media (max-width: 768px) {
  .text-box,
  .imag-l {
    flex: 1 1 100%;
    text-align: center;
  }

  .text-box {
    margin-bottom: 30px;
  }

  .hero .text-box h2 {
    font-size: 1.8rem;
  }

  .hero .text-box p {
    font-size: 1rem;
  }

  .button {
    padding: 12px 25px;
    font-size: 16px;
  }

  .grid-container {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  }

  .footer-section {
    flex: 1 1 100%;
    text-align: center;
    margin-bottom: 20px;
  }

  .social-links {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .hero .text-box h2 {
    font-size: 1.5rem;
  }

  .method-card {
    padding: 20px 15px;
  }

  .method-icon {
    font-size: 2em;
  }

  .method-card h2 {
    font-size: 1.2em;
  }
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
          <li><a style="color: #39ff14;" href="metodo.php">Nosso Método</a></li>
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
        <h2>Quer saber como vamos ensinar programação pra si? <br>Nos explicamos pra você!</h2>
        <p>Nossos metodos de ensino sao desenvolvidos para maximizar sua experiencia de aprendizado, combinando praticas inovadoras com uma abordagem pratica e acessivel. <br> Atraves de uma combinacao de teoria e prática você tera contacto com as ferramentas mais utilizadas no mercado, alem de acompanhamento constante e recursos didaticos</p>
        <a href="aluno/login.php" class="button">Comece a sua jornada</a>
      </div>
      <div class="imag-l">
        <img src="imagens metodo/IMG_1888.PNG" alt="Ilustração de programação">
      </div>
    </div>
  </section>
  
  

  
  
  <section class="methods-section">
    <div class="grid-container">
      <div class="method-card">
        <i class="fas fa-laptop-code method-icon" style="color: #39ff14;"></i>
        <h2>Ensino Prático</h2>
        <p>A prática constante em exercícios de programação é essencial para a fixação do conteúdo.</p>
      </div>
      
      <div class="method-card">
        <i class="fas fa-chalkboard-teacher method-icon" style="color: #39ff14;"></i>
        <h2>Vídeo Aulas</h2>
        <p>Aulas em vídeo explicativas para ensinar os conceitos de forma visual e didática.</p>
      </div>
     
      <div class="method-card">
        <i class="fas fa-book method-icon" style="color: #39ff14;"></i>
        <h2>Material Didático</h2>
        <p>Oferecemos materiais em PDF detalhados para consultas rápidas e revisões aprofundadas.</p>
      </div>
      
      <div class="method-card">
        <i class="fas fa-code method-icon" style="color: #39ff14;"></i>
        <h2>Projetos Reais</h2>
        <p>Desenvolvimento de projetos reais para preparar você para o mercado de trabalho.</p>
      </div>
     
      <div class="method-card">
        <i class="fas fa-users method-icon" style="color: #39ff14;"></i>
        <h2>Colaboração</h2>
        <p>Trabalhamos em equipes para fortalecer habilidades de colaboração e versionamento de código.</p>
      </div>
     
      <div class="method-card">
        <i class="fas fa-brain method-icon" style="color: #39ff14;"></i>
        <h2>Desafios</h2>
        <p>Desafios diários de programação para manter a mente afiada e pronta para novos problemas.</p>
      </div>
      
      <div class="method-card">
        <i class="fas fa-lightbulb method-icon" style="color: #39ff14;"></i>
        <h2>Inovação</h2>
        <p>Trabalhamos com tecnologias e metodologias inovadoras para garantir que você esteja atualizado.</p>
      </div>
      
      <div class="method-card">
        <i class="fas fa-graduation-cap method-icon" style="color: #39ff14;"></i>
        <h2>Acompanhamento Pessoal</h2>
        <p>Oferecemos suporte individual para garantir que você atinja seus objetivos.</p>
      </div>
    </div>
  </section>

 
  
  <section class="hero">
    <div class="container">
      <div class="text-box">
        <h2>Não sabe em qual curso se inscrever? <br>Nos Ajudamos você!</h2>
        <p>Se você esta em duvida sobre qual caminho seguir, nao se preocupe. Nos entendemos que escolher um curso certo pode ser um desafio, por isso estamos aqui para guiar voce nessa jornada, atraves de uma analise das suas habilidades, interesses e objetivos, Oferecemos recomendacoes personalizadas para garantir que voce comece com o pe direito.</p>
      </div>
      <div class="imag-l">
        <img src="imagens metodo/A6E0CEA2-A2FB-4D6E-95F9-38F586295133.PNG" alt="Ajuda para escolher curso">
      </div>
    </div>
  </section>
  
  <div class="footer-container">
    <div class="footer-content">
      <div class="footer-section">
        <h3>Code Masters</h3>
        <ul>
          <li><a href="#">Trabalhe Connosco</a></li>
          <li><a href="#">Arquivo</a></li>
          <li><a href="#">Portal dos alunos</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Navegação</h3>
        <ul>
          <li><a href="#">Início</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Nosso Método</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Políticas e Termos</h3>
        <ul>
          <li><a href="#">Política de Privacidade</a></li>
          <li><a href="#">Termos de Uso</a></li>
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
                    </a></li>
                    <li><a href="https://www.facebook.com/profile.php?id=61577175148785" target="_blank"><img src="login-icones/IMG_1803.PNG" alt="twitter"
                        class="icones-f">
                    </a></li>
      </ul>
        <h3>Fale com a nossa equipe</h3>
        <ul>
          <li><a href="Codemasters.com.ao">Codemasters.com.ao</a></li>
        </ul>
      </div>
    </div>
  </div>
</body>

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

    // Mantenha suas funções JavaScript existentes
    function verificarSuporte() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = 'suporte/suporte.php';
      <?php else: ?>
        alert('Para acessar aqui, você primeiro precisa fazer login.');
        window.location.href = 'aluno/login.php';
      <?php endif; ?>
    }

    function verificarCursos() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = 'gerenciamento/cursos.php';
      <?php else: ?>
        alert('Para acessar aqui, você primeiro precisa fazer login.');
        window.location.href = 'aluno/login.php';
      <?php endif; ?>
    }

    function verificarCertificados() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = 'certificado.php';
      <?php else: ?>
        alert('Para acessar aqui, você primeiro precisa fazer login.');
        window.location.href = 'aluno/login.php';
      <?php endif; ?>
    }

    function verificarEventos() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = 'aluno/eventos.php';
      <?php else: ?>
        alert('Para acessar aqui, você primeiro precisa fazer login.');
        window.location.href = 'aluno/login.php';
      <?php endif; ?>
    }

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
</html>