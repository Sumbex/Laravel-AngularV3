import { Component, OnInit } from '@angular/core';
import { DocuemntacionSindicalService } from '../../servicios/docuemntacion-sindical.service';

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.css']
})
export class IndexComponent implements OnInit {

  tipos:any;
  num=0;
  constructor(private _doc:DocuemntacionSindicalService) { }

  ngOnInit() {
    this.tipos_documentacion();
  }

  tipos_documentacion(){
    const menu = [
      {descripcion:"Secretaria", icono:"fas fa-calendar fa-5x", ruta:"/SociosMaster/Reuniones"},
      {descripcion:"Mi Perfil", icono:"fas fa-users fa-5x", ruta:"/SociosMaster/Perfil"},
      {descripcion:"Mis Beneficios", icono:"fas fa-medal fa-5x", ruta:"/SociosMaster/Beneficios"},
      {descripcion:"Cuentas", icono:"fas fa-money-bill fa-5x", ruta:"/SociosMaster/Cuentas"},
    ];
    this._doc.listado_tipos().subscribe(res=>{
      this.tipos = res.filter(function(x) { return x.id < 5});
      this.tipos[4] = menu[0];
      this.tipos[5] = menu[1];
      this.tipos[6] = menu[2];
      this.tipos[7] = menu[3];

      this.tipos.reverse();
      console.log(this.tipos)
    });
  }

}
