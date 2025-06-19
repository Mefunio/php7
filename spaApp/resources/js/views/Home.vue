<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center">
                    <h1 class="display-4 text-primary mb-4">
                        Statystyki Studentów
                    </h1>
                </div>
                <div class="row mb-5">
                    <div class="col-md-4">
                        <div class="card text-center bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Liczba studentów</h5>
                                <h2 class="card-text">{{ statistics.total || 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Opłaceni</h5>
                                <h2 class="card-text">{{ statistics.paid || 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Nie opłaceni</h5>
                                <h2 class="card-text">{{ statistics.unpaid || 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="paymentChart" width="100" height="49"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            statistics: {
                total: 0,
                paid: 0,
                unpaid: 0
            },
            chart: null
        }
    },
    mounted() {
        this.fetchStatistics();
        this.loadChartJs();
    },
    methods: {
        fetchStatistics() {
            axios.get('/api/student-statistics')
                .then(response => {
                    this.statistics = response.data;
                    this.renderChart();
                })
                .catch(error => {
                    console.error('Error fetching statistics:', error);
                });
        },
        loadChartJs() {
            if (!document.getElementById('chartjs-script')) {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                script.id = 'chartjs-script';
                script.onload = () => this.renderChart();
                document.head.appendChild(script);
            } else {
                this.renderChart();
            }
        },
        renderChart() {
            if (!window.Chart || !this.statistics.total) return;

            const ctx = document.getElementById('paymentChart').getContext('2d');

            if (this.chart) this.chart.destroy();

            this.chart = new window.Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Opłaceni', 'Nie opłaceni'],
                    datasets: [{
                        label: 'Status płatności',
                        data: [this.statistics.paid, this.statistics.unpaid],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(220, 53, 69, 0.7)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const dataset = tooltipItem.dataset;
                                    const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                    const currentValue = dataset.data[tooltipItem.dataIndex];
                                    const percentage = ((currentValue / total) * 100).toFixed(1);
                                    return `${tooltipItem.label}: ${currentValue} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    }
}
</script>

<style scoped>
.card {
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}
</style>
