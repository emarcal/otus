@extends('layouts.app')

@section('content')

<div class="content content-fixed bd-b responsive-submenu">
      <div class="">
        <div class="d-sm-flex align-items-center justify-content-between">
          <div style="width:40%">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb breadcrumb-style1 mg-b-3">
                <li class="breadcrumb-item"><a href="#">System</a></li>
                <li class="breadcrumb-item active" aria-current="page">Status</li>
              </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Análises do Sistema</h4>
          </div>
          <div class="d-none d-md-block">
            <!---
          <a href="#" title="Imprimir">
              <button class="btn btn-sm pd-x-15 btn-red btn-uppercase mg-l-5"><i data-feather="info" class="wd-10 mg-r-5"></i> Informações</button>
            </a>
            <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 "><i data-feather="play-circle" class="wd-10 mg-r-5"></i> Video Tutorial [Inicialização]</button> -->
          </div>
        </div>
      </div>
    </div>
      <div class="content content-fixed all-content" >
        <div class="row row-xs" style="margin-top:125px;padding: 4px 10px">
        
              <a href="calendario">
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
            <div class="row">
           
            <div class="col-8">
            <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Calendário</h6>
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?= @$hoje;?></h3>
                <p class="tx-13 tx-color-03 mg-b-0"> evento(s) para <span class="tx-medium tx-primary">hoje</span></p>
              </div>
                </div>
                <div class="col-4 text-right"> 
             
            </div>
            </div>
           
            </div>
            </a>
          </div><!-- col -->
          <a href="eventos">
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
            <div class="row">
           
            <div class="col-8">
            <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Eventos</h6>
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?= @$eventos;?></h3>
                <p class="tx-13 tx-color-03 mg-b-0"><span class="tx-medium tx-primary"> evento(s) </span> faturados  </p>
              </div>
                </div>
                <div class="col-4"> 
             
            </div>
            </div>
           
            </div>
             </a>
          </div><!-- col -->
          <a href="user">
            <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                <div class="card card-body">
                <div class="row">
              
                <div class="col-8">
                <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Clientes</h6>
                  <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?= @$clientes;?></h3>
                    <p class="tx-13 tx-color-03 mg-b-0"><span class="tx-medium tx-primary">registados  </span>no sistema</p>
                  </div>
                    </div>
                    <div class="col-4 text-right"> 
                 
                </div>
                </div>
              
                </div>
                 </a>
              </div><!-- col -->
       
              <a href="motorista">
            <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
                <div class="card card-body">
                <div class="row">
              
                <div class="col-8">
                <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Motoristas</h6>
                  <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?= @$motoristas;?></h3>
                    <p class="tx-13 tx-color-03 mg-b-0"><span class="tx-medium tx-primary">registados  </span>no sistema</p>
                  </div>
                    </div>
                    <div class="col-4 text-right"> 
                 
                </div>
                </div>
              
                </div>
                 </a>
              </div><!-- col -->
       
     
             

        </div><!-- row -->
        <div class="row row-xs " style="padding: 4px 10px">
        
        <a href="faturamento">
    
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
            <div class="row">
           
            <div class="col-12">
            <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Faturamentos</h6>
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php $total = @$soma; echo number_format($total,2,',','.');?> €</h3>
                <p class="tx-13 tx-color-03 mg-b-0"> faturados nesta<span class="tx-medium tx-primary"> Semana </span> </p>
              </div>
                </div>
            
            </div>
           
            </div>
            
             </a> 
          </div><!-- col -->
          <a href="encargo">
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
            <div class="row">
           
            <div class="col-12">
            <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Encargos</h6>
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php $total = @$somaencargo; echo number_format($total,2,',','.');?> €</h3>
                <p class="tx-13 tx-color-03 mg-b-0">  desta <span class="tx-medium tx-primary"> Semana </span> </p>
              </div>
                </div>
            
            </div>
           
            </div>
            </a> 
             
          </div><!-- col -->
          <a href="imposto">
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
            <div class="row">
           
            <div class="col-12">
            <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">IVA Faturado</h6>
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php $total = @$iva; echo number_format($total,2,',','.');?> €</h3>
                <p class="tx-13 tx-color-03 mg-b-0"> Encargos <span class="tx-medium tx-primary"> <?php $total = @$ivaencargo; echo number_format($total,2,',','.');?> € </span> </p>
              </div>
                </div>
            
            </div>
            
            </div>
            </a> 
             
          </div><!-- col -->
          <a href="#relatorio" data-toggle="modal">
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-body">
            <div class="row">
           
            <div class="col-12">
            <h6 class="tx-uppercase tx-13 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">Relatório</h6>
              <div class="d-flex d-lg-block d-xl-flex align-items-end">
                <h3 class="tx-normal tx-rubik mg-b-0 mg-r-5 lh-1"><?php $total = @$soma - @$somaencargo; echo number_format($total,2,',','.');?> €</h3>
                <p class="tx-13 tx-color-03 mg-b-0"> de  <span class="tx-medium tx-primary"> margem </span> </p>
              </div>
                </div>
            
            </div>
            
            </div>
            </a> 
             
          </div><!-- col -->
        </div><!-- row -->
      
        </div>
      </div><!-- container -->
    </div><!-- content -->
@endsection
