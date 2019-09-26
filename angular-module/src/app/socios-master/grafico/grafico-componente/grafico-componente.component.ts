import { Component, OnInit } from '@angular/core';
import { Chart } from 'chart.js';

@Component({
  selector: 'app-grafico-componente',
  templateUrl: './grafico-componente.component.html',
  styleUrls: ['./grafico-componente.component.css']
})
export class GraficoComponenteComponent implements OnInit {

  chart = [];

  constructor() { }

  ngOnInit() {
    this.chart = new Chart('canvas', {
      type: 'line',
  data: {
    labels: ['Enero','Febrero','Marzo','Abril','Mayo', 'Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    datasets: [{ 
        data: [1500000,1000000,800000],
        label: "Cuenta Sindical",
        backgroundColor: "#3e95cd",
        borderColor: "#3e95cd",
        fill: false
      }, { 
        data: [856000,1200000,1155000],
        label: "Cuenta Bienestar",
        backgroundColor: "#8e5ea2",
        borderColor: "#8e5ea2",
        fill: false
      }, { 
        data: [550000,2220500,1125300],
        label: "Consorcio",
        backrgoundColor: "#3cba9f",
        borderColor: "#3cba9f",
        fill: false
      }, { 
        data: [2150000,1995500,1665000],
        label: "Caja Chica",
        backgroundColor: "#e8c3b9",
        borderColor: "#e8c3b9",
        fill: false
      }, { 
        data: [1350550,1158500,2000000],
        label: "Cuenta del Gas",
        backgroundColor: "#c45850",
        borderColor: "#c45850",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'Estado de las cuentas Año 2019'
    }
  }
    });

  }

}
