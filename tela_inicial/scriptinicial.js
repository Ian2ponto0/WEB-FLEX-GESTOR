// Chart: Pedidos por Mês
new Chart(document.getElementById("ordersChart"), {
    type: 'bar',
    data: {
      labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
      datasets: [{
        label: 'Pedidos 2025',
        data: [10, 15, 8, 20, 18, 25],
        backgroundColor: '#9D00FF'
      }]
    },
    options: {
      plugins: {
        legend: { display: false }
      },
      responsive: true
    }
  });
  
  // Chart: Faturamento vs Despesas
  new Chart(document.getElementById("financeChart"), {
    type: 'line',
    data: {
      labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
      datasets: [
        {
          label: 'Faturamento',
          data: [12000, 15000, 13000, 17000, 14000, 17500],
          borderColor: '#00BFFF',
          fill: false,
          tension: 0.4
        },
        {
          label: 'Despesas',
          data: [6000, 7000, 6500, 8000, 7500, 7200],
          borderColor: '#FF69B4',
          fill: false,
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true
    }
  });
  
  // Chart: Distribuição de Despesas
  new Chart(document.getElementById("expensesChart"), {
    type: 'doughnut',
    data: {
      labels: ['Salários', 'Serviços', 'Infraestrutura', 'Marketing'],
      datasets: [{
        data: [40, 25, 20, 15],
        backgroundColor: ['#FFC300', '#FF69B4', '#9D00FF', '#00BFFF']
      }]
    },
    options: {
      responsive: true
    }
  });
  