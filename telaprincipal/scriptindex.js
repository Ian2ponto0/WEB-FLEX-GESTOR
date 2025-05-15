
new Chart(document.getElementById('graficoDashboard'), {
  type: 'line',
  data: {
    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'],
    datasets: [{
      label: 'Faturamento',
      data: [10000, 12000, 8000, 15000, 20000],
      borderColor: '#00BFFF',
      fill: false,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { labels: { color: 'white' } }
    },
    scales: {
      x: { ticks: { color: 'white' } },
      y: { ticks: { color: 'white' } }
    }
  }
});
