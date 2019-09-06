import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-nacimiento-bienestar',
  templateUrl: './nacimiento-bienestar.component.html',
  styleUrls: ['./nacimiento-bienestar.component.css']
})
export class NacimientoBienestarComponent implements OnInit {
  
  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';

  //variables cuenta Bienestar
  InsertarNacimiento = {
    fecha:'',
    numero_documento_1: '',
    archivo_documento_1: null,
    definicion: '',
    monto: '',
  }

  constructor() {
    }

  ngOnInit() {
  }


}
