<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include 'conexao.php';
$aluno_id = $_SESSION['id'];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/IMG_2517.PNG" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
    <title>Suporte</title>

    <style>

    :root {
            --primary: #4c1d95;
            --secondary: #8b5cf6;
            --accent: #f43f5e;
            --light: #f3e8ff;
            --dark: #2e1065;
            --text: #1e1b4b;
            --text-light: #c4b5fd;
            --white: #ffffff;
        }
  
  *{
    margin: 0px;
    font-family: "Montserrat", sans-serif;
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
    @media (max-width: 1050px) {
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

.container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 50px;
    background-color: white;
    border-radius: 2px;
    box-shadow: 0 4px 0px rgba(0, 0, 0, 0.1);
}

h1, h2, h3 {
    color: #00274d;
}

.faq, .contact-form, .support-options {
    margin-bottom: 40px;
}

.faq h2, .contact-form h2, .support-options h2 {
    margin-bottom: 20px;
}

.faq p {
    margin-bottom: 10px;
}

/* Estilos do formulário */
.contact-form form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.contact-form label {
    font-weight: bold;
}

.contact-form input, .contact-form textarea {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.contact-form button {
    padding: 10px 15px;
    background-color: #004492;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.2em;
}

.contact-form button:hover {
    background-color: #003366;
}

/* Estilos para suporte */
.support-options ul {
    list-style-type: none;
    padding: 0;
}

.support-options li {
    margin-bottom: 10px;
}

.support-options li a {
    color: #004492;
    text-decoration: none;
}

.support-options li a:hover {
    text-decoration: underline;
}
.footer-container {
  background-color: #1f0660;
    
    color: white;
    padding: 40px;
    font-family: Arial, sans-serif;
    position: relative;
    top: 50px;
  }
  
  .footer-content {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
  }
  
  .footer-section {
    flex: 1;
    min-width: 10px;
    margin: 15px;
  }
  
  .footer-section h3 {
    font-size: 1.2em;
    margin-bottom: 10px;
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
  
  .footer-section address {
    font-style: normal;
    line-height: 1.6;
  }
  
  .social-links {
    display: flex;
    gap: 10px;
  }
  
  .social-links li a {
    text-decoration: none;
    color: white;
    
  }.social-links:hover{
    background-color: greenyellow;
  }
  .icones-f{
    width: 30px;
    height: auto;
    display: block;
    margin: 0 auto;
  }
  .icones-f2{
    width: 150px;
    height: auto;
    display: block;
    margin-right: 10px;
  }
  .icones-cm{
    width: 150px;
    height: auto;
    display: block;
    margin-right: 10px;
    border-color: white;
  }


.container-support{
  display: flex;
  justify-content: space-between;
}

       .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .dashboard-title {
            font-size: 2em;
            color: var(--primary);
            margin: 0;
            font-weight: 600;
        }

        .new-ticket-btn {
            background-color: #00274d;
            color: var(--white);
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .new-ticket-btn:hover {
            background-color: var(--primary);
            transform: translateY(-2px);
        }




    </style>
  
</head>
<body>

  <header>
    <div class="nav-container">
      <div class="logo">
        <a href="../index.php"><img src="../logo.png" alt="logo" class="logo-img"></a>
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
          <li><a href="../metodo.php">Nosso Método</a></li>
          <li><a href="../sobre_us.php">Sobre</a></li>
          <li>
            <a style="color: #39ff14;" href="#" onclick="verificarSuporte()">Suporte</a>
          </li>
          <?php if (isset($_SESSION['nome_aluno'])): ?>
            <li><a href="../aluno/dashboard.php" style="color: #39ff14;">Meu Perfil</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>
    <div class="container">

        <h1>Suporte</h1>
        <p>Se você tiver dúvidas ou precisar de ajuda, estamos aqui para assisti-lo. Veja as opções de suporte abaixo.</p>

        <!-- Seção de FAQ -->
        <div class="faq">
            <h2>Perguntas Frequentes (FAQ)</h2>
            <p><strong>Como faço para me inscrever em um curso?</strong></p>
            <p>Para se inscrever em um curso, vá até a página de cursos, escolha o curso de seu interesse e siga as instruções de inscrição.</p>
            
            <p><strong>Esqueci minha senha, como redefinir?</strong></p>
            <p>Clique na opção "Esqueceu sua senha?" na página de login e siga as instruções enviadas para o seu email.</p>
            
            <p><strong>Os cursos possuem certificado?</strong></p>
            <p>Sim, ao concluir o curso, você pode solicitar o certificado digital pela própria plataforma.</p>
        </div>

        <!-- Seção de Formulário de Contato -->
        <div class="contact-form">
            <h2>Fale Connosco</h2>
            <form action="enviar_suporte.php" method="POST">
                <label for="name">Nome:</label>
                <input type="text" id="nome" name="nome" placeholder="Seu Nome" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Seu Email" required>
                
                <label for="subject">Assunto:</label>
                <input type="text" id="subject" name="assunto" placeholder="Assunto" required>

                <label for="message">Mensagem:</label>
                <textarea id="message" name="mensagem" rows="6" placeholder="Digite sua mensagem..." required></textarea>

                <button type="submit">Enviar</button>
            </form>
        </div>

       
        <div class="container-support">
          <div class="support-options">
              <h2>Outras Formas de Suporte</h2>
              <ul>
                  <li><a href="mailto:codemasters222@gmail.com">Suporte via Email</a></li>
                  <li><a href="tel:+244948395096">Suporte via Telefone: +244 123 456 789</a></li>
                  <li><a href="https://wa.me/+948395096">Suporte via WhatsApp</a></li>
                  <li><a href="/tutorials">Ver Tutoriais</a></li>
              </ul>
          </div>

            <div class="dashboard-header">
                <a href="../aluno/mensagem_suporte.php" class="new-ticket-btn">Ver Respostas</a>
            </div>
        </div>
        
        
    </div>
<div class="footer-container">
            <div class="footer-content">
              <div class="footer-section">
                <h3 style="color: rgb(34, 16, 16);">Code Masters</h3>
                <ul>
                  <li><a href="#">Trabalhe Connosco</a></li>
                  <li><a href="#">Arquivo</a></li>
                  <li><a href="#">Portal dos alunos</a></li>
                </ul>
              </div>
              <div class="footer-section">
                <h3 style="color: white;">Navegação</h3>
                <ul>
                  <li><a href="#">Início</a></li>
                  <li><a href="#">Blog</a></li>
                  <li><a href="#">Nosso Método</a></li>
                </ul>
              </div>
              <div class="footer-section">
                <h3 style="color: white;">Políticas e Termos</h3>
                <ul>
                  <li><a href="#" style="color: white;">Política de Privacidade</a></li>
                  <li><a href="#" style="color: white;">Termos de Uso</a></li>
          
                </ul>
              </div>
              <div class="footer-section">
                <h3 style="color: white;">Contatos</h3>
                <ul>
                  <li>Suporte: (244) 925607551</li>
                  <li>Financeiro: (244) 951440702</li>
                  <li>Comercial: (244) 939781010</li>
                </ul>
              </div>
              <div class="footer-section">
                <h3 style="color: white;">Redes Sociais</h3>
                <ul class="social-links">
                  <li><a href="https://www.instagram.com/codemasters22/" target="_blank"><img src="../login-icones/IMG_1802.PNG" alt="linkedin"
                        class="icones-f"></a></li>                  
                    <li><a href="https://youtube.com/@codemasters-p2x?si=GfeY0pREVbNrjd9d" target="_blank"><img src="../login-icones/pngwing.com.png" alt="YouTube"
                        class="icones-f youtube">
                    </a></li>
                  <li><a href="https://www.facebook.com/profile.php?id=61577175148785" target="_blank"><img src="../login-icones/IMG_1803.PNG" alt="twitter"
                        class="icones-f">
                    </a></li>

                </ul>
                <h3 style="color: white;">Fale com a nossa equipe</h3>
                <ul>
                  <li><a href="Codemasters.com.ao" style="color: white;">Codemasters.com.ao</a></li>
                </ul>
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

    // Mantenha suas funções JavaScript existentes
    function verificarSuporte() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = 'suporte.php';
      <?php else: ?>
        alert('Para acessar aqui, você primeiro precisa fazer login.');
        window.location.href = 'aluno/login.php';
      <?php endif; ?>
    }

    function verificarCursos() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = '../gerenciamento/cursos.php';
      <?php else: ?>
        alert('Para acessar aqui, você primeiro precisa fazer login.');
        window.location.href = 'aluno/login.php';
      <?php endif; ?>
    }

    function verificarCertificados() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = '../certificado.php';
      <?php else: ?>
        alert('Para acessar aqui, você primeiro precisa fazer login.');
        window.location.href = 'aluno/login.php';
      <?php endif; ?>
    }

    function verificarEventos() {
      <?php if (isset($_SESSION['nome_aluno'])): ?>
        window.location.href = '../aluno/eventos.php';
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
</body>

</html>