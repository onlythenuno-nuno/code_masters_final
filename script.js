function inscrever(cursoId) {
  fetch("inscrever.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `curso_id=${cursoId}`
  })
  .then(res => res.json())
  .then(data => {
    alert(data.status === "ok" ? "Inscrição realizada!" : "Erro ao inscrever.");
  });
}

function marcarComoEstudado(aulaId, cursoId) {
  fetch("marcar.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `aula_id=${aulaId}&curso_id=${cursoId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "ok") {
      atualizarProgresso();
      alert("Aula marcada como estudada!");
    } else {
      alert(data.msg);
    }
  });
}

function atualizarProgresso() {
  fetch("progresso.php")
    .then(res => res.json())
    .then(data => {
      document.getElementById("porcentagem").innerText = data.porcentagem + "%";
    });
}

window.onload = atualizarProgresso;
