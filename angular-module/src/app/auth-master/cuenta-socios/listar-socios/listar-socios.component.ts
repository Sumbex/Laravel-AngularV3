import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { NgbModalConfig, NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
import { ValidarUsuarioService } from '../../../servicios/validar-usuario.service';
import { SindicalService } from '../../../servicios/sindical.service';

@Component({
  selector: 'app-listar-socios',
  templateUrl: './listar-socios.component.html',
  styleUrls: ['./listar-socios.component.css']
})


export class ListarSociosComponent implements OnInit {

  socios;
  search:string='';
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

  //para validar usuario
   user:object=[];
   load:boolean=false;
   modalReference = null; 
   m_val = null;
   mod_opcion = null;

   closeResult: string;
   pass:string = '';
   btn_validar:boolean = false;

    currentLesson:string;
  
    buttonStatus = false;
    token = localStorage.getItem('token').replace(/['"]+/g, '');
    estado_socio:object=['estado'];
    ver_load:boolean = true;
    ver_estado_soc:boolean = false;

  constructor(private _socios:SociosService, 
              public _validarusuario:ValidarUsuarioService,
              private modalService: NgbModal,
              private _sindical:SindicalService
  ) {

      //this.currentLesson=this.classes[0].currentLesson
  }

  ngOnInit() {

    if (localStorage.getItem('token') == '') {
      alert("La sesión ya expiro!");

      location.reload();
    }

  	this.listar();
    this.usuario_logeado();

  
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
      if(this.search ==''){
          alert("Ingrese un nombre para filtrar");
          return false;
      }else{
    		this._socios.getTablaFilter(this.search).subscribe(
  	      response => {
  	        console.log(response);
  	        this.socios = response;
  	        
  	      }
  	    )
      }
  }

  modal_editar(modal){
  	this.mod_editar = this.modalService.open(modal, { size: 'lg' });
  }
  cerrar_editar(modal){
  	this.mod_editar.close();
  }

  actualizar(id, campo, valor, validar){  

    if (valor.value == '') {
      alert("Ingrese dato en este campo para actualizar");
      return false;

    }
   
    //this.modalReference = this.modalService.open(validar, { size: 'sm' });
    this.m_val = this.modalService.open(validar, {size: 'sm', ariaLabelledBy: 'modal-basic-title'}).result.then((result) => {
        
        this.m_val = this.modalService.open(validar, { size: 'sm' });
        this.load = true;
        this.buttonStatus = true;

        const formData = new FormData();
        formData.append('rut', this.user['rut']);
        formData.append('password', this.pass);

        this._validarusuario.validar_usuario(formData).subscribe((val) => {
    
           // var inputValue = (<HTMLInputElement>document.getElementById("password")).value;
            
            
            if(val > 0){//si tiene acceso;
            
              this.load = false;
              this.buttonStatus = false;
              this.pass = "";
              
             this.m_val.close();
             
                  const form = new FormData();
                  form.append('id', id);
                  form.append('campo', campo);
                  form.append('valor', valor.value);

                  this._socios.getEditar(form).subscribe(
                      response => {
                        if (response.estado == "success") {
                         alert(""+response.mensaje+"");
                         this.listar();
                         this.pass = "";
                        }
                        if (response.estado == "failed") {
                         alert(""+response.mensaje+"");
                         return false;
                        }
                        
                      }
                    );



            }else{
              alert("Acceso denegado");
              this.load = false;
              this.buttonStatus = false;
              this.pass = "";
              this.m_val.close();
              return false;
            }

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
        return false;

      
    }, (reason) => {
      console.log(`${reason}`);
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });


  }




//metodos para validar usuario-------------------------------

  usuario_logeado(){
      
      this._validarusuario.usuario_logeado().subscribe((val : object ) => {
            
            this.user = val;

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
  }

  btn_validar_usuario($rut, $password, validar){//btn que esta en el modal de validacion de usuario
        this.load = true;
        const formData = new FormData();
        formData.append('rut', $rut.value);
        formData.append('password', $password.value);

        this._validarusuario.validar_usuario(formData).subscribe((val) => {
            
            if(val > 0){//si tiene acceso;
            
              this.load = false;
              
             this.modalReference.close();
             return true;
            }else{
              alert("Acceso denegado");
              this.load = false;
              this.modalReference.close();
              return false;
            }

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
    }



  validar_usuario(validar){
       this.modalReference = this.modalService.open(validar, { size: 'sm' });
        window.stop()
  }

  modal_opcion(modal, id){
    this.ver_load = true;
    this.ver_estado_soc = false;
    this.mod_opcion = this.modalService.open(modal, { size: 'lg' });
    this.estado_socio_portal(id);
  }

  cerrar_opcion(){
    this.mod_opcion.close();
  }
  
  asignar_portal(socio_id){

    this._sindical.asignar_portal_socio(socio_id).subscribe(
          response => {
            console.log(response);
            this.ver_load = true;
            this.ver_estado_soc = false;
            this.estado_socio_portal(socio_id);
          }
        )
  }

  estado_socio_portal(socio_id){
    this._sindical.estado_de_socio_en_portal_beneficio(socio_id).subscribe(
          (response:{'estado'}) => {
            this.estado_socio = response.estado;
            this.ver_load = false;
            this.ver_estado_soc = true;
          }
        )
  }


  // fin del metodo para validar usuario



   private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return  `with: ${reason}`;
    }
  }

}
