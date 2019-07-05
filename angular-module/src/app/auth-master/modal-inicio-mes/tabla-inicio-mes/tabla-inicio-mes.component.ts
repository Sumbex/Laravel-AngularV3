import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { AniosService } from 'src/app/servicios/anios.service';
//import-------------
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global'
/////////////////////



@Component({
  selector: 'app-tabla-inicio-mes',
  templateUrl: './tabla-inicio-mes.component.html',
  styleUrls: ['./tabla-inicio-mes.component.css']
})
export class TablaInicioMesComponent implements OnInit {
  selectAnio: Anios[] = [];
  //variables-----------------
  anio:string = '';
  public url: string;
  tabla:object=[];
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  //-------------------------

  constructor(public _http: HttpClient) {
  		//global---------------
  		this.url = global.url;
  		//---------------------
   }

  ngOnInit() {
    //Cargar Años
    //this.anio_actual();
    //this.selectAnio = JSON.parse(localStorage.getItem('anios'));

  }


  //metodos---------------------------------------------------------------------------------------
		// anio_actual(){
		// 		this._http.get(this.url + "anio_actual",
		// 	  		    	  {headers: new HttpHeaders({'Authorization': 'Bearer' + this.token })}
		// 	  	).subscribe((val : { id, descripcion } ) => {

		// 	    			this.anio = val.id;
		// 	    			this.listar_tabla();
			        
		// 	    }, response => {console.log("POST call in error", response);},() => {
		// 	           console.log("The POST success.");
		// 	    });
		// }


	 //  	listar_tabla() {
		//   	this._http.get(this.url + "listar_inicio_y_cierre_mensual_cs/"+this.anio ,{headers: new HttpHeaders(
		//             {'Authorization': 'Bearer' + this.token})}
		//   	).subscribe((val : object ) => {
		     		
		//      		this.tabla = val;

		//     }, response => {console.log("POST call in error", response);},() => {
		//            console.log("The POST success.");
		//     });
	 //  	}

		// cambiar_anio(){
		//   	this.listar_tabla();
		// }
}
