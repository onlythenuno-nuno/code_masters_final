<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/IMG_2517.PNG" type="image/x-icon">
    <title>Sobre Nós | CODE MASTERS</title>
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

        /* Estilos adicionais específicos para sobre_nos */
        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: #39ff14;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: #170448;
            border: 4px solid #39ff14;
            border-radius: 50%;
            top: 15px;
            z-index: 1;
        }
        
        .left {
            left: 0;
            text-align: right;
        }
        
        .right {
            left: 50%;
            text-align: left;
        }
        
        .left::after {
            right: -12px;
        }
        
        .right::after {
            left: -12px;
        }
        
        .timeline-content {
            padding: 20px;
            background-color: #1f0660;
            border: 1px solid #39ff14;
            border-radius: 10px;
            position: relative;
        }
        
        .location-card {
            background-color: #1f0660;
            border: 2px solid #39ff14;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: 40px auto;
            text-align: center;
        }
        
        .location-card i {
            font-size: 2.5em;
            color: #39ff14;
            margin-bottom: 20px;
        }
        
        
        @media (max-width: 768px) {
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item::after {
                left: 21px;
            }
            
            .left, .right {
                left: 0;
                text-align: left;
            }
        }

        .hero{
          background-image: url(ChatGPT\ Image\ 5\ de\ jun.\ de\ 2025\,\ 23_13_28.png);
          background-attachment: fixed;
          background-size: cover;
          background-position: top;
          min-height: 70vh;
          box-shadow: inset 0px -15px 20px 0px rgba(0, 0, 0, 0.71);
          display: flex;
          align-items: center;
        }

        .hero > .container > .text-box{
          font-weight: bold;
          color: white;
          text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.63);
        }

        .text-box h2 {
          font-size: 2.2rem;
          margin-bottom: 20px;
        }

        .text-box p {
          font-size: 1.1rem;
          margin-bottom: 30px;
          line-height: 1.6;
          text-align: justify;
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
          border-radius: 40px;
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
          <li><a style="color: #39ff14;" href="sobre_us.php">Sobre</a></li>
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
        <h2>CODE MASTERS</h2>
        <p>Um centro de formação criado e desenvolvido por programadores com um método de ensino inovador, capaz de ensinar alunos de várias origens formativas. Fundado em 20 de Março de 2015, nosso centro fornece um programa educacional completo que visa oferecer conhecimentos teóricos e práticos sobre linguagens de programação e desenvolvimento de software.</p>
        
      </div>
      <div class="imag-l">
        <a href="aluno/login.php" class="button">Junte-se a nós</a>
      </div>
    </div>
  </section>


  <section class="methods-section" style="background-color: #1f0660; padding: 50px 20px;">
    <div class="container">
      <div class="imag-l">
        <img src="multiuso1.png">
      </div>
      <div class="text-box">
        <h2>Nossa História</h2>
        <p>O CODE MASTERS foi fundado por jovens programadores apaixonados por tecnologia e educação. Nosso objetivo sempre foi democratizar o acesso ao conhecimento tecnológico de qualidade, levando o ensino de programação para todos os interessados.</p>
        <p>Localizado na Vila Alice, Rua 10 (Rua da Liberdade), nosso centro se destaca por sua abordagem focada no ensino de habilidades tecnológicas práticas e atualizadas.</p>
      </div>
    </div>
  </section>

  <section class="methods-section">
    <h2 style="text-align: center; margin-bottom: 40px;">Nossa Linha do Tempo</h2>
    <div class="timeline">
      <div class="timeline-item left">
        <div class="timeline-content">
          <h3>2015</h3>
          <p>Fundação do CODE MASTERS em 20 de Março por um grupo de jovens programadores</p>
        </div>
      </div>
      <div class="timeline-item right">
        <div class="timeline-content">
          <h3>2017</h3>
          <p>Primeira turma formada com 30 alunos capacitados em desenvolvimento web</p>
        </div>
      </div>
      <div class="timeline-item left">
        <div class="timeline-content">
          <h3>2019</h3>
          <p>Expansão para novos cursos incluindo mobile development e data science</p>
        </div>
      </div>
      <div class="timeline-item right">
        <div class="timeline-content">
          <h3>2021</h3>
          <p>Lançamento da plataforma online de ensino com aulas remotas</p>
        </div>
      </div>
      <div class="timeline-item left">
        <div class="timeline-content">
          <h3>2023</h3>
          <p>Mudança para nova sede na Vila Alice com laboratórios modernos</p>
        </div>
      </div>
    </div>
  </section>

  <section class="methods-section" style="background-color: #170448; padding: 50px 20px;">
    <div class="container">
      <div class="text-box">
        <h2>Nossa Missão</h2>
        <p>Criar uma plataforma de ensino capaz de proporcionar uma experiência educacional imersiva e interativa, integrando ensino à distância, práticas de codificação em tempo real e suporte contínuo.</p>
        <p>Com o avanço das tecnologias digitais, nos adaptamos constantemente para oferecer cursos que englobam desde aplicativos e sites até sistemas de gerenciamento de dados e software complexos.</p>
      </div>
      <div class="imag-l">
        <img src="lab_1.png" alt="Aulas práticas no CODE MASTERS">
      </div>
    </div>
  </section>

  <div class="location-card">
    <i class="fas fa-map-marker-alt"></i>
    <h2>Onde Estamos</h2>
    <p>Vila Alice, Rua 10 (Rua da Liberdade)</p>
    <p>Luanda, Angola</p>
  </div>

  <section class="methods-section">
    <h2 style="text-align: center; margin-bottom: 30px;">Conheça Nossas Instalações</h2>
    <div class="grid-container">
      <div class="method-card">
        <i class="fas fa-laptop-house method-icon" style="color: #39ff14;"></i>
        <h2>Laboratórios Modernos</h2>
        <p>Equipados com computadores de última geração e todas as ferramentas necessárias para seu aprendizado.</p>
      </div>
      <div class="method-card">
        <i class="fas fa-chalkboard method-icon" style="color: #39ff14;"></i>
        <h2>Salas de Aula</h2>
        <p>Ambientes climatizados e projetados para o melhor conforto durante suas aulas.</p>
      </div>
      <div class="method-card">
        <i class="fas fa-wifi method-icon" style="color: #39ff14;"></i>
        <h2>Infraestrutura Digital</h2>
        <p>Internet de alta velocidade disponível em todo o centro para suas pesquisas e projetos.</p>
      </div>
      <div class="method-card">
        <i class="fas fa-coffee method-icon" style="color: #39ff14;"></i>
        <h2>Área de Convivência</h2>
        <p>Espaço descontraído para networking e troca de ideias com outros alunos e instrutores.</p>
      </div>
    </div>
  </section>

  <!-- Footer igual ao da página de método -->
  <div class="footer-container">
    <div class="footer-content">
      <div class="footer-section">
        <h3>CODE MASTERS</h3>
        <ul>
          <li><a href="#">Trabalhe Connosco</a></li>
          <li><a href="#">Arquivo</a></li>
          <li><a href="#">Portal dos alunos</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Navegação</h3>
        <ul>
          <li><a href="index.php">Início</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="sobre_nos.php">Sobre Nós</a></li>
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
                    </a></li><li><a href="https://www.facebook.com/profile.php?id=61577175148785" target="_blank"><img src="login-icones/IMG_1803.PNG" alt="twitter"
                        class="icones-f">
                    </a></li>
      </ul>
        <h3>Fale com a nossa equipe</h3>
        <ul>
          <li><a href="mailto:info@codemasters.com.ao">info@codemasters.com.ao</a></li>
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