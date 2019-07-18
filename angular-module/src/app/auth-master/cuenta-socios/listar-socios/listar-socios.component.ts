import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ValidarUsuarioService } from '../../../servicios/validar-usuario.service';

@Component({
  selector: 'app-listar-socios',
  templateUrl: './listar-socios.component.html',
  styleUrls: ['./listar-socios.component.css']
})
export class ListarSociosComponent implements OnInit {

  socios;
  search;
  mod_editar = null;
  mod_validar = null;

  //actualizar_socio
  rut;
  nombres;
  a_paterno;
  a_materno;
  fecha_nacimiento;
  fecha_ingreso;
  fecha_egreso;

  constructor(private _socios:SociosService, public _validarusuario:ValidarUsuarioService,private modalService: NgbModal) { }

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

  modal_editar(modal){
  	this.mod_editar = this.modalService.open(modal, { size: 'lg' });
  }
  cerrar_editar(modal){
  	this.mod_editar.close();
  }

  actualizar(id, campo, valor){

  	const form = new FormData();
  	form.append('id', id);
  	form.append('campo', campo);
  	form.append('valor', valor.value);

  	this._socios.getEditar(form).subscribe(
	      response => {
	        if (response.estado == "success") {
	        	alert(""+response.mensaje+"");
	        	this.listar();
	        }
	        if (response.estado == "failed") {
	        	alert(""+response.mensaje+"");
	        	return false;
	        }
	        
	      }
	    );
  }

}
