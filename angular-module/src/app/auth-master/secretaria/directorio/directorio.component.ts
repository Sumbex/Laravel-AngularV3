import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { DirectorioService } from 'src/app/servicios/directorio.service';

@Component({
  selector: 'app-directorio',
  templateUrl: './directorio.component.html',
  styleUrls: ['./directorio.component.css']
})
export class DirectorioComponent implements OnInit {

  datosDirectorio = {
  fecha : '',
  sec_tipo_reunion_id : '',
  titulo : '',
  presidente : false,
  tesorero : false,
  secretario : false,
  primerDirector : false,
  segundoDirector : false,
  asistencia : '',
  temas : '',
  acuerdos : ''
  };

  //loading
  ingresandoDatos = false;

  //listar
  listarDirectorio;
  listarDirectorioDetalle;

  //variable para asociar al modal
  modalHistorial;
  modalDetalle;


  constructor(
    config: NgbModalConfig, 
    private modalService: NgbModal, 
    private _directorio: DirectorioService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }
  ngOnInit() {
  }

  abrirModal(modalDirectiva){
    this.modalHistorial = this.modalService.open(modalDirectiva, {size: 'xl'});
  }
  abrirModalDetalle(modalDetalle,id){
    this.traerDetalle(id);
    this.modalDetalle = this.modalService.open(modalDetalle, {size: 'xl'});
  }

  ingresarDirectorio(form){
    if(!form){
      alert('Ha ocurrido un error con el formulario, comprobar que no tenga errores');
    }else{
      this.ingresandoDatos = true;
      this._directorio.registroDirectorio(this.datosDirectorio).subscribe(response => {
        if(response.estado == 'failed' || response.estado == 'failed_v'){
          alert(response.mensaje);
          this.ingresandoDatos = false;
        }else{
          alert(response.mensaje);
          this.limpiarCampos();
          this.ingresandoDatos = false;
        }
      },error=>{
        console.log((error));
        this.ingresandoDatos = false;
      });
    }
  }

  limpiarCampos(){
    this.datosDirectorio.fecha = '';
    this.datosDirectorio.sec_tipo_reunion_id = '';
    this.datosDirectorio.titulo = '';
    this.datosDirectorio.presidente = false;
    this.datosDirectorio.tesorero= false;
    this.datosDirectorio.secretario = false;
    this.datosDirectorio.primerDirector = false;
    this.datosDirectorio.segundoDirector = false;
    this.datosDirectorio.asistencia = '';
    this.datosDirectorio.temas = '';
    this.datosDirectorio.acuerdos = '';
  }

  visualizarDirectorio(){
    this._directorio.visualizarDirectorio().subscribe(response => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
        this.modalHistorial.close();
      } else {
        this.listarDirectorio = response.listar;
        console.log(this.listarDirectorio);
        
      }
    }, error => {
      console.log(error);
    });
  }

  traerDetalle(id){
    this._directorio.visualizarDirectorioDetalle(id).subscribe(response => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
      } else {
        this.listarDirectorioDetalle = response.listar;
        console.log(this.listarDirectorioDetalle);

        
      }
    }, error => {
      console.log(error);
    });
  } 
}
