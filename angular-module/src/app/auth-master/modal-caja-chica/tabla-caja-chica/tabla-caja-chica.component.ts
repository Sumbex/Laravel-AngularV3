import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-tabla-caja-chica',
  templateUrl: './tabla-caja-chica.component.html',
  styleUrls: ['./tabla-caja-chica.component.css']
})
export class TablaCajaChicaComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];

  constructor() { }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

}
