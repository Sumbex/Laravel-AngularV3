import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-tabla-inicio-mes',
  templateUrl: './tabla-inicio-mes.component.html',
  styleUrls: ['./tabla-inicio-mes.component.css']
})
export class TablaInicioMesComponent implements OnInit {
  selectAnio: Anios[] = [];

  constructor() { }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
  }

}
