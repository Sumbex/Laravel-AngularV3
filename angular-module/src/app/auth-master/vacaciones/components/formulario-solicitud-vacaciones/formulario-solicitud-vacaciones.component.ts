import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-formulario-solicitud-vacaciones',
  templateUrl: './formulario-solicitud-vacaciones.component.html',
  styleUrls: ['./formulario-solicitud-vacaciones.component.css']
})
export class FormularioSolicitudVacacionesComponent implements OnInit {

  blockIngreso:boolean = false;
  constructor() { }

  ngOnInit() {
  }

}
