package ufsic.scheme.thymeleaf;

import java.util.HashMap;
import java.util.Map;
import org.thymeleaf.Arguments;
import org.thymeleaf.dom.NestableAttributeHolderNode;
import org.thymeleaf.dom.Node;
import org.thymeleaf.processor.AbstractProcessor;
import org.thymeleaf.processor.IProcessorMatcher;
import org.thymeleaf.processor.ProcessorMatchingContext;
import org.thymeleaf.processor.ProcessorResult;
import ufsic.scheme.Scheme;
import ufsic.utils.Utils;

public class SchemeVariablesProcessor extends AbstractProcessor implements IProcessorMatcher {

  private Map<String,IVariableExtractor> extractors = new HashMap<>();
  private Scheme scheme;
  
  public SchemeVariablesProcessor(Scheme scheme) {
    
    super();
    this.scheme = scheme;
  }

  public void setExtractor(String name, IVariableExtractor extractor) {
    extractors.put(name,extractor);
  }
  
  @Override
  public int getPrecedence() {
    return 1;
  }

  /*private String getTemplate(Node parent) {
    
    String ret = null;
    if (Utils.isNotNull(parent)) {
      
      if (parent instanceof Document) {
        
        Document node = (Document)parent;
        if (node.hasChildren()) {
          
          
          
          List<Node> list = node.getChildren();
          for (Node n: list) {
            ret = n.getDocumentName();
            break;
          }
        }
        Map<String,Attribute> map = node.getAttributeMap();
        
        for (Entry<String,Attribute> entry: map.entrySet()) {
          
          String name = entry.getKey();
          if (name.equalsIgnoreCase(StandardReplaceFragmentAttrProcessor.ATTR_NAME) ||
              name.equalsIgnoreCase(StandardSubstituteByFragmentAttrProcessor.ATTR_NAME)) {
            Attribute a = entry.getValue();
            ret = a.getValue();
            break;
          }
        }
        
        if (Utils.isNull(ret) && parent.hasParent()) {
          ret = getTemplate(parent.getParent());
        }
      }
    }
    return ret;
  }*/
  
  @Override
  protected ProcessorResult doProcess(Arguments arguments, ProcessorMatchingContext processorMatchingContext, Node node) {
    
    String template = node.getDocumentName();
    if (Utils.isNotNull(template)) {
      
      IVariableExtractor extractor = extractors.get(template);
      if (Utils.isNotNull(extractor)) {
        
        Map<String,Object> vars = extractor.getVariables();
        if (Utils.isNotNull(vars)) {
          node.setAllNodeLocalVariables(vars);
          
        }
      }
    }
    return ProcessorResult.ok();
  }

  @Override
  public IProcessorMatcher<? extends Node> getMatcher() {
    return this;
  }

  @Override
  public boolean matches(Node node, ProcessorMatchingContext context) {
    return true;
  }

  @Override
  public Class appliesTo() {
    return NestableAttributeHolderNode.class;
  }
  
  
}
