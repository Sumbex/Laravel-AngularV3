import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-beneficios-socios',
  templateUrl: './beneficios-socios.component.html',
  styleUrls: ['./beneficios-socios.component.css']
})
export class BeneficiosSociosComponent implements OnInit { 

  
  rut: string = '';

  constructor(
    private _SociosService:SociosService
  ) { }

  ngOnInit() {
  }

  buscarSocio(){
    this._SociosService.traerDatosSocio(this.rut).subscribe((res) => {
      console.log(res);
    },
    error => {
      console.log(error);
    }
    );
    
  }

}
