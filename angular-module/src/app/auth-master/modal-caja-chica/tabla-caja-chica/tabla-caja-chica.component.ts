import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { cajaChicaSindical } from 'src/app/modelos/cajaChicaSindical.model';
import { CajaChicaService } from 'src/app/servicios/caja-chica.service';

@Component({
  selector: 'app-tabla-caja-chica',
  templateUrl: './tabla-caja-chica.component.html',
  styleUrls: ['./tabla-caja-chica.component.css']
})
export class TablaCajaChicaComponent implements OnInit {

  constructor(private _cajaChicaService : CajaChicaService) { }

  ngOnInit() {

  }

}
