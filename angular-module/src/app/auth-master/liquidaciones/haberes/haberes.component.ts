import { Component, OnInit } from '@angular/core';

import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { LiquidacionJuanitoService } from '../../../servicios/liquidacion-juanito.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
@Component({
  selector: 'app-haberes',
  templateUrl: './haberes.component.html',
  styleUrls: ['./haberes.component.css']
})
export class HaberesComponent implements OnInit {
  modalActualizar = null;

  constructor(config: NgbModalConfig,
      private modalService: NgbModal,
      public _http: HttpClient,
      private _liq: LiquidacionJuanitoService,
      private _validarusuario: ValidarUsuarioService
  ) { }
  
  load:boolean=false;
  load_table:boolean=false;
  user: object = [];

  empleado='';
  empleados:object=[
    {'id':1, 'nombre':'Alejandro Godoy'},
    {'id':2, 'nombre': 'Manuel Garcia' },
  ];

  haber="";
  haberes:object;
  tipo='';

  id_hab='';
  valor='';

  tabla:object;
  valor_update='';
  m_val = null;
  buttonStatus = false;
  pass='';
  actualizarLoad: boolean = false;

  

    ngOnInit() {
      this.listar_hab();
    }

    openActualizar(Actualizar) {
      this.modalActualizar = this.modalService.open(Actualizar, { size: 'sm' });
      this.usuario_logeado();

    }

    cerrarActualizar() {
      this.modalActualizar.close();
      // this.actualizarMontoCajaChica = '';
    }


  //metodos para validar usuario-------------------------------

    usuario_logeado() {

      this._validarusuario.usuario_logeado().subscribe((val: object) => {

        this.user = val;

      }, response => { console.log("POST call in error", response); }, () => {
        console.log("The POST success.");
      });
    }

    listar_hab(){
     
      this._liq.lista_haberes().subscribe(
        response => {
          if (response.status == "success") {
              this.haberes = response.lista;
             
          }
          error => {
            console.log(<any>error);
          }
        }
      );
    }

  seleccionar_h(){
    this.valor = '';
    var tipo = this.haber.substr(0, 1); 
    var cadena = this.haber.split(/\s*|\s*/); 

   
    var id = '';
    for (let index = 0; index < cadena.length; index++) {
      if (index >= 2){
        id += cadena[index];
      }
    }
  
    this.tipo = tipo;
    this.id_hab = id;
  }

  registrar(){
    this.load = true;
    this._liq.save_haber({ 
        'id_empleado': this.empleado,
        'id_hab': this.id_hab, 
        'tipo': this.tipo, 
        'valor': this.valor 
      }).subscribe(
      response => {

        if (response.estado=='failed'){
          alert(""+response.mensaje+"");
          this.load = false;
        }else{
          this.listar_h();
          this.haber = "";
          this.valor = "";
          this.load = false;
        }

          
        // if (response.status == "success") {
        //   this.haberes = response.lista;

        // }
        error => {
          console.log(<any>error);
          alert("Algo salió mal, ingrese los datos nuevamente");
          this.load = false;
        }
      }
    );
  }

  listar_h(){
    this.load_table = true;
    this._liq.listar_haber(this.empleado).subscribe(
      response => {
        if (response.estado == "success") {
          this.tabla = response.lista;
          this.load_table = false;

        }
        error => {
          console.log(<any>error);
        }
      }
    );
  }

  eliminar_conf_hab(id){

    var r = confirm("¿Quiere eliminar este item?");

    //cancel clicked : stop button default action 
    if (r === true) {
      this._liq.eliminar_conf_haber(id).subscribe(
        response => {
          if (response.estado == "success") {
            this.listar_h();
            alert("" + response.mensaje + "");

          }
          if (response.estado == "failed") {
            alert("" + response.mensaje + "");

          }
          error => {
            console.log(<any>error);
          }
        }
      );
    }else{
      alert("Proceso frenado");
    }


    

  }





  //actualizar items
  actualizar(id, campo, input, validar){
    
    if (this.valor_update.trim() == '') {
      alert("ingrese datos porfavor!");
      return false;
    }
    this.m_val = this.modalService.open(validar, { size: 'sm', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {

      this.m_val = this.modalService.open(validar, { size: 'sm' });
      this.load = true;
      this.buttonStatus = true;

      const formData = new FormData();
      formData.append('rut', this.user['rut']);
      formData.append('password', this.pass);
      formData.append('estado', 'modificar_cs');

      this._validarusuario.validar_usuario(formData).subscribe((val) => {

        //si tiene acceso
        if (val > 0) {

          this.load = false;
          this.buttonStatus = false;
          this.pass = "";

          this.m_val.close();

          const form = new FormData();
          form.append('id', id);
          form.append('campo', campo);
          form.append('valor', this.valor_update.trim());

          this.actualizarLoad = true;
         //aqui metodo para actualizar
          this._liq.actualizar_conf_haber(form).subscribe(
            response => {
              if (response.estado == "success") {
                this.listar_h();
                alert("" + response.mensaje + "");
                this.modalActualizar.close();
                this.actualizarLoad = false;
                this.pass = "";
                this.valor_update = "";

              }
              if (response.estado == "failed") {
                alert("" + response.mensaje + "");
                this.actualizarLoad = false;
                this.pass = "";
                this.valor_update = "";
                return false;

              }
              error => {
                console.log(<any>error);
              }
            }
          );



        } else {
          alert("Acceso denegado");
          this.load = false;
          this.buttonStatus = false;
          this.pass = "";
          this.m_val.close();
          return false;
        }

      }, response => { console.log("POST call in error", response); }, () => {
        console.log("The POST success.");
      });
      return false;


    }, (reason) => {
      console.log(`${reason}`);
      //this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });


  }


}
