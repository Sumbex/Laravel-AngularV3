import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../servicios/global';
import { ThrowStmt } from '@angular/compiler';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-modal-detalle-pago-consorcio',
  templateUrl: './modal-detalle-pago-consorcio.component.html',
  styleUrls: ['./modal-detalle-pago-consorcio.component.css']
})
export class ModalDetallePagoConsorcioComponent implements OnInit {

  directiva:string = '';
  directivas=[];
  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  modal;
  monto_inicio = 105000;
  tabla = [];
  ahorro ={
    total_ahorro_dia_sueldo:0
  };
  cpds={
    total:0
  };//consorcio_pago_dia_sueldo
  total=0;
  cargar = false;
  load:boolean=false;
  user: object = [];
  //actualizar
  entrada;
  archivoDocumento;
  // selectDefinicion: Definicion[] = [];
  modalActualizar = null;
  buttonStatus;
  actualizarLoad;
  m_val = null;
  anio;
  anios;
  pass: string = '';
  nombre:string = '';
  constructor(config: NgbModalConfig,
     private modalService: NgbModal,
    public _http: HttpClient, 
    private _validarusuario: ValidarUsuarioService,
    private _fechasService: AniosService,
     ) { 
    this.url = global.url;
     }
  
  
  ngOnInit() {
    console.log('el kkck',this.tabla)
    this.usuario_logeado()

    this.anios = JSON.parse(localStorage.getItem('anios'));
  }

  openModal(modal) {
    this.modal = this.modalService.open(modal, { size: 'xl' });
    this.cargar_select();
  
  }
  open(content) {
    this.modalService.open(content, { size: 'lg' });
  }
  openActualizar(Actualizar) {
    this.modalActualizar = this.modalService.open(Actualizar, { size: 'sm' });
    this.usuario_logeado();

  }
  openMontoRestante(Actualizar) {
    this.modalActualizar = this.modalService.open(Actualizar, { size: 'lg' });
    this.usuario_logeado();

  }
  cerrarActualizar() {
    this.modalActualizar.close();
    // this.actualizarMontoCajaChica = '';
  }
  cargar_select() {

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.anio = response.id;
        this.cargar = true;
        this.tablita();
        // this.idAnioActual = response;
        // this.valorAnio.descripcion = this.idAnioActual.id;
        // this.suc_res1 = true;

        // this.listo_para_listar(this.suc_res1, this.suc_res2);

      },
      error => {
        console.log(error);
      }
    )

  }
  usuario_logeado() {

    this._validarusuario.usuario_logeado().subscribe((val: object) => {

      this.user = val;

    }, response => { console.log("POST call in error", response); }, () => {
      console.log("The POST success.");
    });
  }
  // get_directivas(){
  //   this._http.get(this.url + "traer_directivas", {
  //     headers: new HttpHeaders(
  //       { 'Authorization': 'Bearer' + this.token, } )}).subscribe(
  //         (val: { 'estado', 'data','actual' }) => {
  //     if (val.estado == "success") {
  //       this.directivas = val.data;
  //       this.directiva = val.actual.id
  //       this.cargar = true;
  //       // this.tablita();

  //     }
  //     if (val.estado == "failed") {


  //     }
  //   }, response => {
  //     console.log("POST call in error", response);
  //   },
  //     () => {
  //       console.log("The POST observable is now completed.");
  //     });
  // }

  tablita(){
    console.log(this.directiva)
    if(this.cargar == true){
      this._http.get(this.url + "listar_consorcio_pago_dia_sueldo/" + this.anio , {
          headers: new HttpHeaders(
            {
              'Authorization': 'Bearer' + this.token,
              //'Content-Type': 'application/form-data'
            }
          )
        }).subscribe((val: { 'estado', 'data' }) => {
          //console.log(val.estado);
          if (val.estado == "success") {
            this.tabla = val.data;

            this.resultados();

          }
          if (val.estado == "failed") {
            this.tabla = [];
            this.ahorro = { total_ahorro_dia_sueldo: 0}
            this.cpds = { total: 0}
            this.total = 0;

          }
        }, response => {
          console.log("POST call in error", response);
        },
          () => {
            console.log("The POST observable is now completed.");
          });
    }
  }

  tabla_filtro(){
    
    if(this.nombre == ''){
      alert('No hay nombre para buscar'); return false;
    }

    this._http.get(this.url + "listar_socio_consorcio_pago_dia_sueldo/"+this.nombre+'/'+ this.anio, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
        }
      )
    }).subscribe((val: { 'estado', 'data' }) => {
      //console.log(val.estado);
      if (val.estado == "success") {
        this.tabla = val.data;

        this.resultados();

      }
      if (val.estado == "failed") {
        this.tabla = [];
        this.ahorro = { total_ahorro_dia_sueldo: 0 }
        this.cpds = { total: 0 }
        this.total = 0;

      }
    }, response => {
      console.log("POST call in error", response);
    },
      () => {
        console.log("The POST observable is now completed.");
      });
  }

  resultados() {
    this._http.get(this.url + "traer_total_ahorro_dia_sueldo/"+this.anio, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
        }
      )
    }).subscribe((val: { 'estado','ahorro', 'cpds', 'total' }) => {
      //console.log(val.estado);
      if (val.estado == "success") {
        this.ahorro = val.ahorro;
        this.cpds = val.cpds;
        this.total = val.total

      }
      if (val.estado == "failed") {

        this.ahorro = { total_ahorro_dia_sueldo: 0 }
        this.cpds = { total: 0 }
        this.total = 0;

      }
    }, response => {
      console.log("POST call in error", response);
    },
      () => {
        console.log("The POST observable is now completed.");
      });
  }








  actualizar(id, campo, input, validar) {
    if (campo == "archivo") {
      if (campo == "undefined") {
        alert("ingrese documento porfavor!")
        return false;
      }
      this.entrada = this.archivoDocumento;
    } else {
      this.entrada = input.value;
    }
    if (this.entrada == '') {
      alert("ingrese datos porfavor!");
      return false;
    }
    this.m_val = this.modalService.open(validar, { size: 'sm', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {

      this.m_val = this.modalService.open(validar, { size: 'sm' });
      // this.load = true;
      this.buttonStatus = true;

      const formData = new FormData();
      formData.append('rut', this.user['rut']);
      formData.append('password', this.pass);
      formData.append('estado', 'actualizar_ds');

      this._validarusuario.validar_usuario(formData).subscribe((val) => {

        //si tiene acceso
        if (val > 0) {

          this.load = true;
          this.buttonStatus = false;
          this.pass = "";

          this.m_val.close();

          const form = new FormData();
          form.append('id', id);
          form.append('campo', campo);
          form.append('valor', /*input.value*/this.entrada);

          this.actualizarLoad = true;
          
         //post aqui
          this._http.post(this.url + "actualizar_cpds", form,{
            headers: new HttpHeaders(
              { 'Authorization': 'Bearer' + this.token, })
          }).subscribe(
            (val: { 'estado', 'mensaje' }) => {
              if (val.estado == "success") {
                this.cerrarActualizar()
                alert(val.mensaje)
                // this.directivas = val.data;
                // this.directiva = val.actual.id
                 this.actualizarLoad = false;
                 this.load = false;
                 this.cargar = true;
                 this.tablita();
                 this.resultados();
              }
              if (val.estado == "failed") {
                this.cerrarActualizar()
                alert(val.mensaje)
                this.actualizarLoad = false;
                this.load = false;

              }
            }, response => {
              console.log("POST call in error", response);
            },
            () => {
              console.log("The POST observable is now completed.");
            });



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

  onSelectImage(event) {
    this.archivoDocumento = event.srcElement.files[0];
  }

  cerrar_estado(ahorro){
      // console.log(ahorro);
    var opcion = confirm("¿Seguro quiere cerrar el estado del total de ahorro de día de sueldo?");
    if (opcion == true) {
      this._http.get(this.url + "cerrar_estado_dia_sueldo/" + this.anio/*ahorro.id*/, {
        headers: new HttpHeaders(
          {
            'Authorization': 'Bearer' + this.token,
            //'Content-Type': 'application/form-data'
          }
        )
      }).subscribe((val: { 'estado', 'mensaje' }) => {
        //console.log(val.estado);
        if (val.estado == "success") {
            alert(""+val.mensaje+"");

        }
        if (val.estado == "failed") {
          alert("" + val.mensaje + "");

        }
      }, response => {
        console.log("POST call in error", response);
      },
        () => {
          console.log("The POST observable is now completed.");
        });
    } else {
      
    }
      
  }


}
