import { Component, OnInit } from '@angular/core';

import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { LiquidacionJuanitoService } from '../../../servicios/liquidacion-juanito.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';

@Component({
  selector: 'app-descuentos',
  templateUrl: './descuentos.component.html',
  styleUrls: ['./descuentos.component.css']
})
export class DescuentosComponent implements OnInit {

  modalActualizar = null;

  constructor(config: NgbModalConfig,
    private modalService: NgbModal,
    public _http: HttpClient,
    private _liq: LiquidacionJuanitoService,
    private _validarusuario: ValidarUsuarioService
  ) { }

  load: boolean = false;
  load_table: boolean = false;
  user: object = [];

  empleado = '';
  empleados: object;
  suma=0;
  // empleados:object=[
  //   {'id':1, 'nombre':'Alejandro Godoy'},
  //   {'id':2, 'nombre': 'Manuel Garcia' },
  // ];

  haber = "";
  haberes: object;
  tipo = '';

  id_desc = '';
  valor = '';

  tabla: object;
  valor_update = '';
  m_val = null;
  buttonStatus = false;
  pass = '';
  actualizarLoad: boolean = false;
  formula='';

  resumen:object={
    'sueldo_base': {monto:0},
    'suma_i':0,
    'suma_h':0
  };



  ngOnInit() {
    this.usuario_logeado();
    this.traer_empleados();
    
    this.listar_desc();

  }

  openActualizar(Actualizar) {
    this.modalActualizar = this.modalService.open(Actualizar, { size: 'sm' });
    // this.usuario_logeado();

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

  traer_empleados() {

    this._liq.traer_empleados().subscribe(
      response => {
        if (response.estado == "success") {
          this.empleados = response.empleado;
          console.log(this.empleados);
        }
        error => {
          console.log(<any>error);
        }
      }
    );
  }

  listar_desc() {

    this._liq.lista_descuentos().subscribe(
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

  seleccionar_d() {
    this.valor = '';
    var tipo = this.haber.substr(0, 1);
    var cadena = this.haber.split(/\s*|\s*/);


    var id = '';
    for (let index = 0; index < cadena.length; index++) {
      if (index >= 2) {
        id += cadena[index];
      }
    }
    

    this.tipo = tipo;
    this.id_desc = id;
    this._liq.formula_desc(this.id_desc).subscribe(
      response => {
        this.formula = response;
        console.log(this.formula)
      });
  }
  limpiar_tabla_header(){
    this.resumen = {
      'sueldo_base': { monto: 0 },
      'suma_i': 0,
      'suma_h': 0
    };
  }

  registrar() {
    this.load = true;
    this._liq.save_descuentos({
      'id_empleado': this.empleado,
      'id_desc': this.id_desc,
      'tipo': this.tipo,
      'valor': this.valor.replace(',','.')
    }).subscribe(
      response => {

        if (response.estado == 'failed') {
          alert("" + response.mensaje + "");
          this.id_desc = "";
          this.haber = "";
          this.valor = "";
          this.formula ="";
          this.load = false;
        } else {
          this.listar_d();
          this.haber = "";
          this.valor = "";
          this.formula ="";
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

  listar_d() {
    this.suma=0;
    this.load_table = true;

    this.tabla_total_h_i();
    this._liq.listar_descuentos(this.empleado).subscribe(
      response => {
        if (response.estado == "success") {
          this.tabla = response.lista;
          this.suma = response.suma;
          this.load_table = false;

        }
        if(response.estado=='failed'){
           
          this.tabla = [];
          this.limpiar_tabla_header();
          this.load_table = false;
        }
        error => {
          console.log(<any>error);
        }
      }
    );
  }

  eliminar_conf_desc(id) {

    var r = confirm("¿Quiere eliminar este item?");

    //cancel clicked : stop button default action 
    if (r === true) {
      this._liq.eliminar_conf_descuento(id).subscribe(
        response => {
          if (response.estado == "success") {
            this.listar_d();
            alert("" + response.mensaje + "");
            this.modalActualizar.close();
            this.actualizarLoad = false;
            this.pass = "";
            this.valor_update = "";

          }
          if (response.estado == "failed") {
            alert("" + response.mensaje + "");
            this.modalActualizar.close();
            this.actualizarLoad = false;
            this.pass = "";
            this.valor_update = "";

          }
          error => {
            console.log(<any>error);
          }
        }
      );
    } else {
      alert("Proceso frenado");
      this.modalActualizar.close();
      this.actualizarLoad = false;
      this.pass = "";
      this.valor_update = "";
    }




  }





  //actualizar items
  actualizar(id, campo, input, validar) {

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
      formData.append('estado', 'modificar_descuento');

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
          this._liq.actualizar_conf_descuento(form).subscribe(
            response => {
              if (response.estado == "success") {
                this.listar_d();
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

  eliminar_validar(id, validar) {


    this.m_val = this.modalService.open(validar, { size: 'sm', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {

      this.m_val = this.modalService.open(validar, { size: 'sm' });
      this.load = true;
      this.buttonStatus = true;

      const formData = new FormData();
      formData.append('rut', this.user['rut']);
      formData.append('password', this.pass);
      formData.append('estado', 'eliminar_descuento');

      this._validarusuario.validar_usuario(formData).subscribe((val) => {

        //si tiene acceso
        if (val > 0) {

          this.load = false;
          this.buttonStatus = false;
          this.pass = "";

          this.m_val.close();

          this.actualizarLoad = true;
          //aqui metodo para actualizar
          this.eliminar_conf_desc(id);



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

   tabla_total_h_i(){
     this._liq.traer_total_h_i(this.empleado).subscribe(
       response => {
         if (response.estado == "success") {
           this.resumen = response;
           
         }
         error => {
           console.log(<any>error);
         }
       }
     );
   }
}
