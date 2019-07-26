import { Component, OnInit } from '@angular/core';
import { AniosService } from '../../../servicios/anios.service';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';

@Component({
  selector: 'app-interes-prestamo-apuro-economico',
  templateUrl: './interes-prestamo-apuro-economico.component.html',
  styleUrls: ['./interes-prestamo-apuro-economico.component.css']
})
export class InteresPrestamoApuroEconomicoComponent implements OnInit {

  suc_res1 = false;
  suc_res2 = false;
  anio:string='';
  anios;
  mes:string='';
  meses;
  tabla:object=[];
  total:string='';
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http: HttpClient,public _anios: AniosService) {

  	this.url = global.url;
  }

  ngOnInit() {
  	//Cargar Años
      this.anios = JSON.parse(localStorage.getItem('anios'));

      //Cargar Meses
      this.meses = JSON.parse(localStorage.getItem('meses'));

      this.cargar_select();
  }

   cargar_select(){
     
      this._anios.getAnioActual().subscribe((val: {descripcion, id}) => {
              this.anio = val.id;
              this.suc_res1 = true;

              this.listo_para_listar(this.suc_res1, this.suc_res2);
              
                
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
      
      this._anios.getMesActual().subscribe((val:{descripcion, id}) => {
              this.mes = val.id;
              this.suc_res2 = true;
              
              this.listo_para_listar(this.suc_res1, this.suc_res2);
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });

  }

  listar_intereses(){
  	 this._http.get(this.url + "listar_interes_prestamo/"+this.anio+'/'+this.mes ,{headers: new HttpHeaders(
                {'Authorization': 'Bearer' + this.token})}
        ).subscribe((val : {tabla,total} ) => {
            
            this.tabla = val.tabla;
            this.total = val.total;

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
  }

  listo_para_listar(res1, res2){
    if (res1 == true && res2 == true) {
      console.log("ready to get");
     
      this.listar_intereses();

    }
  }

  change_anio(){
  	this.listo_para_listar(this.suc_res1, this.suc_res2);
  }
  change_mes(){
  	this.listo_para_listar(this.suc_res1, this.suc_res2);
  }


}
