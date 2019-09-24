import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { NgbModalConfig, NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from '../../../servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { ConsorcioService } from 'src/app/servicios/consorcio.service';

@Component({
  selector: 'app-formulario-fondo-mutuo',
  templateUrl: './formulario-fondo-mutuo.component.html',
  styleUrls: ['./formulario-fondo-mutuo.component.css']
})
export class FormularioFondoMutuoComponent implements OnInit {


  socios;
  search: string = '';
  mod_editar = null;
  mod_validar = null;
  blockLoad = false;
  blockLoad2 = false;

  //actualizar_socio
  rut;
  nombres;
  a_paterno;
  a_materno;
  fecha_nacimiento;
  fecha_ingreso;
  fecha_egreso;

  //para validar usuario
  user: object = [];
  load: boolean = false;
  modalReference = null;
  m_val = null;
  mod_opcion = null;

  closeResult: string;
  pass: string = '';
  btn_validar: boolean = false;

  currentLesson: string;

  buttonStatus = false;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  estado_socio: object = ['estado'];
  ver_load: boolean = true;
  ver_estado_soc: boolean = false;

   //variables select año y mes
   anio; anios;
   mes; meses;
   idAnioActual;
   idMesActual;
   suc_res1 = false;
   suc_res2 = false;
//ingresar consorcio
   blockIngreso: boolean = false;
   InsertarCuentaConsorcio = {
     socioId: '',
     anioId: '',
     mesId: '',
     tipo_consorcio: '',
     monto: '',
     estado_socio: '',
   }
   tipo_consorcio;
   monto;

  constructor(private _socios: SociosService,
    private _time: AniosService,
    public _validarusuario: ValidarUsuarioService,
    private _consorcioService: ConsorcioService,
    private _sindical: SindicalService
  ) {

  }

  ngOnInit() {

    if (localStorage.getItem('token') == '') {
      alert("La sesión ya expiro!");

      location.reload();
    }

    this.listar();
    this.llenar_select();
  }

  listar() {
    this._consorcioService.listar_consorcio().subscribe(
      response => {
        console.log(response);
        this.socios = response;
        this.blockLoad2 = false;

      }
    )
  }

  // filtrar() {

  //   this.blockLoad = true;
  //   if (this.search == '') {
  //     alert("Ingrese un nombre para filtrar");
  //     this.blockLoad = false;
  //     return false;
  //   } else {
  //     this._socios.getTablaFilter(this.search).subscribe(
  //       response => {
  //         console.log(response);
  //         this.socios = response;
  //         this.blockLoad = false;

  //       }
  //     )
  //   }
  // }

  // fin del metodo para validar usuario
  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
  }

  llenar_select() {

    this._time.getAnios().subscribe(
      response => {
        this.anios = response;


        this._time.getAnioActual().subscribe(
          (response: { id }) => {
            this.anio = response.id;
            this.suc_res1 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);

          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )

    this._time.getMeses().subscribe(
      response => {
        this.meses = response;

        this._time.getMesActual().subscribe(
          (response: { id }) => {
            this.mes = response.id;
            this.suc_res2 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);
          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )
  }

  listo_para_listar(res1, res2) {
    if (res1 == true && res2 == true) {

    }
  }

  btn_reload() {

    this.listo_para_listar(this.suc_res1, this.suc_res2);

  }

  insertar_consorcio(){
    if (this.InsertarCuentaConsorcio == null) {
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    this.blockIngreso = true;
    const data = new FormData();
    data.append('socio_id', this.InsertarCuentaConsorcio.socioId);
    data.append('anio_id', this.InsertarCuentaConsorcio.anioId);
    data.append('mes_id', this.InsertarCuentaConsorcio.mesId);
    data.append('tipo_consorcio', this.InsertarCuentaConsorcio.tipo_consorcio);
    data.append('monto', this.InsertarCuentaConsorcio.monto);
    data.append('estado_socio', this.InsertarCuentaConsorcio.estado_socio);
    console.table(data);

    this._consorcioService.insertar_consorcio(data).subscribe((response) => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
        this.blockIngreso = false;
        return false;
      }
      if (response.estado == 'failed_v') {
        alert("Verifique que los campos no esten duplicados");
        this.blockIngreso = false;
        return false;
      }
      if (response.estado == 'success') {
        this.InsertarCuentaConsorcio.socioId = '';
        this.InsertarCuentaConsorcio.anioId = '';
        this.InsertarCuentaConsorcio.mesId = '';
        this.InsertarCuentaConsorcio.tipo_consorcio = '';
        this.InsertarCuentaConsorcio.monto = '';
        this.InsertarCuentaConsorcio.estado_socio = '';
        alert(response.mensaje);
        this.blockIngreso = false;
        return false;
      }
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );
  }


}
