import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';

@Component({
  selector: 'app-listar-socios',
  templateUrl: './listar-socios.component.html',
  styleUrls: ['./listar-socios.component.css']
})
export class ListarSociosComponent implements OnInit {

  socios;
  search;
  constructor(private _socios:SociosService) { }

  ngOnInit() {
  	this.listar();
  }

  listar(){
	  	this._socios.getTablaSocios().subscribe(
	      response => {
	        console.log(response);
	        this.socios = response;
	        
	      }
	    )
  }

  filtrar(){
  		this._socios.getTablaFilter(this.search).subscribe(
	      response => {
	        console.log(response);
	        this.socios = response;
	        
	      }
	    )
  }

}
