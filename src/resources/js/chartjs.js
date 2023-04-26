// import Chart from "chart.js/auto";
// import axios from 'axios';

// window.onload = function() {
//     const ctx = document.getElementById("myChart").getContext("2d");
//     const myChart = new Chart(ctx, {
//         type: "line",
//         data: {
//             // labels: ,コントローラから取得
//             datasets: [
//                 {
//                     // label: ,コントローラから取得
//                     spanGaps : true,
//                     // data: ,コントローラから取得
//                     borderColor: "rgb(30, 144, 255)",
//                     backgroundColor: "rgba(135, 206, 250, 0.5)",
//                 },
//             ],
//         },
//     });

//     // Laravelのチャートデータ取得処理の呼び出し
//     axios
//         .get("/chart-get")
//         .then((response) => {
//             console.log(response.data);
//             // Chartの更新
//             myChart.data.labels = response.data.date;
//             myChart.data.datasets[0].label = response.data.title_name;
//             myChart.data.datasets[0].data = response.data.amount;
//             myChart.update();
//         })
//         .catch(() => {
//             alert("グラフのデータ取得に失敗しました");
//         });
// }
