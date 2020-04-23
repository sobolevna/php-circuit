# php-circuit
First try of circuit built with PHP 

The circuit is a programming paradigm based on the following: 
1. All can be seen as a structure 
2. Structure consists of elements and connections between them 
3. Even a simple element, withiout any inner elements, is a structure, you just call it a simple one 
4. There are 3 types of elements: 
    1. Nodes are key elements of the structure
    2. Entry points are used to make connections between structures 
    3. Empty fields are optional and can be used to define subtypes. You can fill it in with a structure

Entity: Core, Limitation, Particularity 
Structure: elements and connections
StructuredEntity: Node, EntryPoint, EmptyField and connections between them
StructuredElement: Element that extends StructuredEntity
StructuredConnection: connection that can connect StructuredElements. It contains connection between EntryPoints. Only EntryPoints with the same type (or if one type extends another) can be connected
ComplexEntity: StructuredEntity with more than 3 elements
ComplexElement: Element that extends ComplexEntity 
ComplexConnection: connection that can connect ComplexElements. It tries to connect every EntryPoint of each ComplexElement with another one's using rules of StructuredConnection
Container: can contain any object and use it 
Processor: can handle Processable 
Process: structured dataflow 
Circuit: implements all of it 

Problems: 
* Structure Building 
* Processes 
* Element accessing 
* Practical usage 